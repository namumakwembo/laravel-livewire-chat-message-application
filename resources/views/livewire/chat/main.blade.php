<div>


    <div class="chat_container">

        <div class="chat_list_container">

            @livewire('chat.chat-list')

        </div>

        <div class="chat_box_container">

            @livewire('chat.chatbox')

            @livewire('chat.send-message')
        </div>
    </div>


    <script>
        window.addEventListener('chatSelected', event => {

            if (window.innerWidth < 768) {

                $('.chat_list_container').hide();
                $('.chat_box_container').show();

            }

            $('.chatbox_body').scrollTop($('.chatbox_body')[0].scrollHeight);
        let height= $('.chatbox_body')[0].scrollHeight; 
    //alert(height);
    window.livewire.emit('updateHeight',{
       
height:height,


    });
        });


        $(window).resize(function() {

            if (window.innerWidth > 768) {
                $('.chat_list_container').show();
                $('.chat_box_container').show();

            }

        });


        $(document).on('click', '.return', function() {

            $('.chat_list_container').show();
            $('.chat_box_container').hide();


        });
    </script>

<script>
    // //let el= $('#chatBody');
    // let el = document.querySelector('#chatBody');
    // window.addEventListener('scroll', (event) => {
    //     // handle the scroll event 
    //     alert('aasd');
    
    // });
    $(document).on('scroll','#chatBody',function() {
        alert('aasd');
    
        var top = $('.chatbox_body').scrollTop();
        if (top == 0) {
            
            window.livewire.emit('loadmore');
        }
    
    
    });
    
    </script>
</div>
