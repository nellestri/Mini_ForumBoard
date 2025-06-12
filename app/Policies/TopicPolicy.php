<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    public function update(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id || $user->isAdmin();
    }

    public function delete(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id || $user->isAdmin();
    }
}
