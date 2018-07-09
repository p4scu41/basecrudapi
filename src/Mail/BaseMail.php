<?php

namespace p4scu41\BaseCRUDApi\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Mail Base Class
 *
 * @category Mail
 * @package  p4scu41\BaseCRUDApi\Mail
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-07-06
 */
class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * View
     *
     * @var string
     */
    public $template = '';

    /**
     * Data send to view
     *
     * @var array
     */
    public $data = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $template = '', array $data = [])
    {
        $this->template = !empty($template) ? $template: $this->template;
        $this->data     = !empty($data) ? $data        : $this->data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->withSwiftMessage(function (\Swift_Message $message) {
                // By default the Content-Transfer-Encoding set to quoted-printable
                // but it parses and prints white space and line breaks
                // the right encoding is Base64 or 8Bit
                // https://www.w3.org/Protocols/rfc1341/5_Content-Transfer-Encoding.html
                $message->setEncoder(new \Swift_Mime_ContentEncoder_Base64ContentEncoder());

                // $headers = $message->getHeaders();
                // $headers->remove('Content-Transfer-Encoding');
                // $headers->addTextHeader('Content-Transfer-Encoding', '8bit');
            })
            ->view($this->template)
            ->with($this->data);
    }
}
