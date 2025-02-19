I need to add  to EntryController.php

i want to make a pipe that adds to the entry using prism embeddings and  other things

something like
// Create a pipeline to handle additional tasks
        app(Pipeline::class)
            ->send($user)
            ->through([
                \App\Pipes\CreateSquareUser::class,
                // \App\Pipes\CreateGoHighLevelUser::class,
                // \App\Pipes\CreateArrayUser::class,

            ])
            ->then(function ($user) {
                return $user;
            });

I'm leaving off on <https://youtu.be/Rd3RTTF8xYI?si=fxtImuQdQxcRejJZ&t=945>
