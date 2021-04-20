<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends BaseModel
{
    protected $guarded = ['id'];
    protected $appends = ['set_smtp_message'];

    public function verifySmtp()
    {
        if ($this->mail_driver == 'smtp') {
            try {
                $transport = new \Swift_SmtpTransport($this->mail_host, $this->mail_port, $this->mail_encryption);
                $transport->setUsername($this->mail_username);
                $transport->setPassword($this->mail_password);

                $mailer = new \Swift_Mailer($transport);
                $mailer->getTransport()->start();

                if ($this->verified == 0) {
                    $this->verified = 1;
                    $this->save();
                }

                return [
                    'success' => true,
                    'message' => __('messages.smtpSuccess')
                ];
            } catch (\Swift_TransportException $e) {
                $this->verified = 0;
                $this->save();
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            } catch (\Exception $e) {
                $this->verified = 0;
                $this->save();
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        } else {
            return [
                'success' => true,
                'message' => __('messages.smtpSuccess')
            ];
        }
    }

    public function getSetSmtpMessageAttribute()
    {
        if ($this->verified === 0 && $this->mail_driver == 'smtp') {
            return ' <div class="alert alert-danger">
                    ' . __('messages.smtpNotSet') . '
                    <a href="' . route('admin.email-settings.index') . '" class="btn btn-info btn-small">Visit SMTP Settings <i
                                class="fa fa-arrow-right"></i></a>
                </div>';
        }
        return null;
    }
}
