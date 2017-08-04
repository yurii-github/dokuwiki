<?php

/**
 * Class action_plugin_swiftmail
 *
 * https://swiftmailer.symfony.com/docs/messages.html
 *
 */
class action_plugin_swiftmail extends \DokuWiki_Action_Plugin {

    /**
     * Return a string usable as EHLO message
     *
     * @param string $ehlo configured EHLO (ovverrides automatic detection)
     * @return string
     */
    static public function getEHLO($ehlo='') {
        if(empty($ehlo)) {
            $ehlo = !empty($_SERVER["SERVER_ADDR"]) ? "[" . $_SERVER["SERVER_ADDR"] . "]" : "localhost.localdomain";
        }
        return $ehlo;
    }

    /**
     * @param Doku_Event_Handler $controller
     */
    public function register(\Doku_Event_Handler $controller){
        $controller->register_hook('MAIL_MESSAGE_SEND', 'BEFORE', $this, 'eventMailMessageSend');
    }

    /**
     * @param Doku_Event $event
     * @param $args
     */
    public function eventMailMessageSend(\Doku_Event $event, $args) {
        /** @var Mailer $dokuMailer Our Mailer with all the data */
        //TODO: replace dokuwiki mailer and stuff
        $dokuMailer =& $event->data['mail'];
        $dokuMailer->dump();

        // setup event
        $event->preventDefault();
        $event->stopPropagation();
        $count = 0;

        // setup true mailer
        $transport = (new Swift_SmtpTransport($this->getConf('smtp_host'), $this->getConf('smtp_port'), $this->getConf('smtp_ssl')))
            ->setUsername($this->getConf('auth_user'))
            ->setPassword($this->getConf('auth_pass'))
            ->setLocalDomain(self::getEHLO($this->getConf('localdomain')));

        $mailer = new Swift_Mailer($transport);

        if($this->getConf('debug')){
            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));
        }

        //send
        try {
            // multipart/alternative | text/html | 'text/plain'
            $message = (new Swift_Message('Wonderful Subject'))
                ->setSubject($event->data['subject'])
                ->setFrom($event->data['from'])
                ->setTo($event->data['to'])
                ->setCc($event->data['cc'])
                ->setBcc($event->data['bcc'])
                ->setBody($dokuMailer->getText(), 'text/plain')
                ->setCharset('UTF-8');

            if ($GLOBALS['conf']['htmlmail']) {
                $message->addPart($dokuMailer->getHTML(), 'text/html');
            }

            $count = $mailer->send($message);
        }
        catch (Exception $e) {
            msg('There was an unexpected problem communicating with SMTP: '.$e->getMessage(), -1);
        }
        finally {
            if ($this->getConf('debug')) {
                msg(str_replace("\n",'<br>', $logger->dump()), ($count > 0 ? 0 : -1));
            }
        }

        //TODO: batch mail support
        //TODO: Sendmail $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');*/
        $event->result = (bool)$count;;
        $event->data['success'] = (bool)$count;;
    }
}