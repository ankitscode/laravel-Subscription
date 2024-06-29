<?php

namespace App\Observers;

class MyAuditingObs
{
    public function creating($post)
    {
        $post->created_by = auth()->id();
        $post->updated_by = auth()->id();
    }

    public function updating($post)
    {
        $post->updated_by = auth()->id();
    }
}
