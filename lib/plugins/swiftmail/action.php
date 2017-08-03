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

       // dbg('zzzzzzzzzz');
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
        $result = false;

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
            $message = (new Swift_Message('Wonderful Subject'))
                ->setSubject($event->data['subject'])
                ->setFrom($event->data['from'])
                ->setCc($event->data['cc'])->setBcc($event->data['bcc'])
                ->setTo($event->data['to'])
                ->setBody($event->data['body'], $GLOBALS['conf']['htmlmail'] ? 'text/html' : 'text/plain');
            // TODO: optionally an alternative body
            // $message->addPart($html, 'text/html');
            $count = $mailer->send($message);
            $result = (bool)$count;
            //dbg($event->data);
        }
        catch (Exception $e) {
            msg('There was an unexpected problem communicating with SMTP: '.$e->getMessage(), -1);
        }
        finally {
            if ($this->getConf('debug')) {
                msg("Logger DUMP:\n".str_replace("\n",'<br>',$logger->dump()), ($count > 0 ? 0 : -1));
            }
        }


        //TODO: Sendmail $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');*/

        $event->result = $result;
        $event->data['success'] = $result;
    }
}