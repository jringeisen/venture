<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class FeedbackCreated extends Notification
{
    use Queueable;

    public function __construct(
        public Feedback $feedback
    ) {
    }

    public function via(): array
    {
        return ['slack'];
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->headerBlock('Feedback Received')
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('User: '.$this->feedback->user->name.' '.$this->feedback->user->email);
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('Title: '.$this->feedback->title);
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('Description: '.$this->feedback->description);
            });
    }
}
