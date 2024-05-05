<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Photo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

new #[Layout('layouts.app')] class extends Component {
    public $show = false;
    public $title = '';
    public $message = '';
    public $type = 'none';
    public $duration = 3;

    protected $listeners = [
        'showToast' => 'showToast',
    ];

    public function mount()
    {
        $this->message = __('No message');

        if (session()->has('toast')) {
            $this->message = session('toast');
            $this->showToast($this->message, 'success', 3);
        }
    }

    public function showToast(string $message, string $type, int $duration): void
    {
        $this->message = $message;
        $this->show = true;
        $this->type = $type;
        $this->duration = $duration;
    }
};

?>
<div>
    <div class="fixed left-20 bottom-10 ">

        <div x-show="$wire.show" x-effect="if($wire.show) setTimeout(()=>$wire.show=false, $wire.duration*1000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="flex justify-between rounded-[8px] gap-6 bg-gray-600 text-white p-[16px] w-auto min-w-[300px] ">

            @switch($type)
                @case('check')
                    <div class="border-r border-r-2 pr-[16px]"><x-icons.check-circle-filled class="fill-white" /></div>
                @break

                @case('warning')
                    <div class="border-r border-r-2 pr-[16px]"><x-icons.warning-filled class="fill-white" /></div>
                @break

                @case('info')
                    <div class="border-r border-r-2 pr-[16px]"><x-icons.info-circle-filled class="fill-white" /></div>
                @break

                @case('error')
                    <div class="border-r border-r-2 pr-[16px]"><x-icons.error-square-filled class="fill-white" /></div>
                @break
            @endswitch

            <div>
                {{ $message }}
            </div>

            <style>
                .another-circle {
                    stroke-dasharray: 95;
                    animation: draw {{ $duration }}s linear forwards;
                    transform-origin: 0px 0px 0px;
                }

                @keyframes draw {
                    0% {
                        stroke-dashoffset: 0;
                    }

                    100% {
                        stroke-dashoffset: 95px;
                    }
                }
            </style>

            <div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    width="25px" height="25px" viewBox="0 0 26 26" xml:space="preserve">
                    <circle transform="rotate(0 26 26)" class="another-circle" cx="13" cy="13" r="12"
                        fill="transparent" stroke="white" stroke-width="1.9" />
                </svg>
            </div>

        </div>

    </div>
</div>
