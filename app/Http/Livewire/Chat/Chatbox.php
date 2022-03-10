<?php

namespace App\Http\Livewire\Chat;

use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;


class Chatbox extends Component
{

    public $selectedConversation;
    public $receiver;
    public $messages;
    public $paginateVar = 10;
    public $height;

    // protected $listeners = [ 'loadConversation', 'pushMessage', 'loadmore', 'updateHeight', "echo-private:chat. {$auth_id},MessageSent"=>'broadcastedMessageReceived',];


    public function  getListeners()
    {

        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'loadConversation', 'pushMessage', 'loadmore', 'updateHeight','broadcastMessageRead','resetComponent'
        ];
    }



    public function resetComponent()
  {
 
$this->selectedConversation= null;
$this->receiverInstance= null;
 
      # code...
  }

    public function broadcastedMessageRead($event)
    {
        //dd($event);

        if($this->selectedConversation){



            if((int) $this->selectedConversation->id === (int) $event['conversation_id']){

                $this->dispatchBrowserEvent('markMessageAsRead');
            }

        }

        # code...
    }
    /*---------------------------------------------------------------------------------------*/
    /*-----------------------------Broadcasted Event fucntion-------------------------------------------*/
    /*----------------------------------------------------------------------------*/

    function broadcastedMessageReceived($event)
    {
        ///here 
      $this->emitTo('chat.chat-list','refresh');
        # code...
        
        $broadcastedMessage = Message::find($event['message']);


        #check if any selected conversation is set 
        if ($this->selectedConversation) {
            #check if Auth/current selected conversation is same as broadcasted selecetedConversationgfg
            if ((int) $this->selectedConversation->id  === (int)$event['conversation_id']) {
                # if true  mark message as read
                $broadcastedMessage->read = 1;
                $broadcastedMessage->save();
                $this->pushMessage($broadcastedMessage->id);
                // dd($event);

                $this->emitSelf('broadcastMessageRead');
            }
        }
    }


    public function broadcastMessageRead( )
    {
        broadcast(new MessageRead($this->selectedConversation->id, $this->receiverInstance->id));
        # code...
    }

    /*--------------------------------------------------*/
    /*------------------push message to chat--------------*/
    /*------------------------------------------------ */
    public function pushMessage($messageId)
    {
        $newMessage = Message::find($messageId);
        $this->messages->push($newMessage);
        $this->dispatchBrowserEvent('rowChatToBottom');
        # code...
    }



    /*--------------------------------------------------*/
    /*------------------load More --------------------*/
    /*------------------------------------------------ */
    function loadmore()
    {

        // dd('top reached ');
        $this->paginateVar = $this->paginateVar + 10;
        $this->messages_count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id',  $this->selectedConversation->id)
            ->skip($this->messages_count -  $this->paginateVar)
            ->take($this->paginateVar)->get();

        $height = $this->height;
        $this->dispatchBrowserEvent('updatedHeight', ($height));
        # code...
    }


    /*---------------------------------------------------------------------*/
    /*------------------Update height of messageBody-----------------------*/
    /*---------------------------------------------------------------------*/
    function updateHeight($height)
    {

        // dd($height);
        $this->height = $height;

        # code...
    }



    /*---------------------------------------------------------------------*/
    /*------------------load conersation----------------------------------*/
    /*---------------------------------------------------------------------*/
    public function loadConversation(Conversation $conversation, User $receiver)
    {


        //  dd($conversation,$receiver);
        $this->selectedConversation =  $conversation;
        $this->receiverInstance =  $receiver;


        $this->messages_count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id',  $this->selectedConversation->id)
            ->skip($this->messages_count -  $this->paginateVar)
            ->take($this->paginateVar)->get();

        $this->dispatchBrowserEvent('chatSelected');

        Message::where('conversation_id',$this->selectedConversation->id)
         ->where('receiver_id',auth()->user()->id)->update(['read'=> 1]);

        $this->emitSelf('broadcastMessageRead');
        # code...
    }
    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
