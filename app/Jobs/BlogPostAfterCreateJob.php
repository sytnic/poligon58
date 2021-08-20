<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BlogPost;

class BlogPostAfterCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /* Dispatchable - его методы делают отложеннный запуск (dispatch) или сразу (dispatchNow),
       InteractsWithQueue - управление объектом очереди (сколько попыток, удалить из очереди и т.д.),
       Queueable - логика работы джоба,
       SerializesModels - подтягивание модели.
    */

    /**
     * @var BlogPost
     */
    private $blogPost;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost;
        
        // задаёт желаемую очередь для джоба (задачи),
        // т.е., видимо, выполнять в Очереди эту задачу (default - имя джоба из БД) 
        //$this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logs()->info("Создана новая запись в блоге[{$this->blogPost->id}]");
    }
}
