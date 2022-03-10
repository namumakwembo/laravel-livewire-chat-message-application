<div>
    {{-- Care about people's approval and you will be their prisoner. --}}

    <div class="chatlist_header">

        <div class="title">
            Chat
        </div>

        <div class="img_container">
            <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{auth()->user()->name}}" alt="">
        </div>
    </div>

    <div class="chatlist_body">

        @if (count($conversations) > 0)
            @foreach ($conversations as $conversation)
                <div class="chatlist_item " wire:key='{{$conversation->id}}' wire:click="$emit('chatUserSelected', {{$conversation}},{{$this->getChatUserInstance($conversation, $name = 'id') }})">
                    <div class="chatlist_img_container">

                        <img src="https://ui-avatars.com/api/?name={{$this->getChatUserInstance($conversation, $name = 'name')}}"
                            alt="">
                    </div>

                    <div class="chatlist_info">
                        <div class="top_row">
                            <div class="list_username">{{ $this->getChatUserInstance($conversation, $name = 'name') }}
                            </div>
                            <span class="date">
                                {{ $conversation->messages->last()?->created_at->shortAbsoluteDiffForHumans() }}</span>
                        </div>

                        <div class="bottom_row">

                            <div class="message_body text-truncate">
                                {{ $conversation->messages->last()->body }}
                            </div>
                 
                            @php
                                if(count($conversation->messages->where('read',0)->where('receiver_id',Auth()->user()->id))){

                             echo ' <div class="unread_count badge rounded-pill text-light bg-danger">  '
                                 . count($conversation->messages->where('read',0)->where('receiver_id',Auth()->user()->id)) .'</div> ';

                                }

                            @endphp

                        </div>
                    </div>
                </div>



            @endforeach


        @else
            you have no conversations
        @endif

    </div>
</div>
