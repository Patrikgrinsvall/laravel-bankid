<div class="container flex px-5 py-10 mx-auto">
    <div class="relative z-10 flex flex-col w-full p-8 mt-10 bg-white rounded-lg shadow-lg border-1 border-solid  lg:w-1/3 md:w-1/2 md:m-auto md:mt-0">
        <h2 class="mb-1 text-lg font-semibold text-gray-900 title-font">
            <span wire:model="message" >{{$message}}</span>
        </h2>

        <div class="relative flex items-stretch flex-grow mb-4 focus-within:z-10">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <!-- Heroicon name: solid/users -->
              <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
              </svg>
            </div>
            @error('name') <span class="error">{{{ $message }}}</span> @enderror


            @if($orderRef != '') <div wire:poll="collect()" wire:model.defer="message"></div> @endif
            @if($status == 'pending') <div wire:poll="collect()" wire:model.defer="message"></div> @endif
            @if($status == 'complete') {{{ $message }}} @endif


            <input
                wire:model.lazy="personalNumber"
                wire:keydown.enter="authenticate"
                wire:click="$emitSelf('personalNumberClick')"
                type = "text" name="personalNumber"
                class="px-3 py-2 pl-10 border rounded shadow-md appearance-none text-grey-darker"
                placeholder="{{ $personalNumber }}"
            />

        </div>

        <div class="pt-5">
            <div class="flex justify-center">
                <a href="{{ config('bankid.cancelUrl')}}"><button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button></a>
                <button
                    wire:click = "authenticate"
                    class = "{{ $statusClass }} inline-flex justify-center px-4 py-2 ml-3 text-sm font-medium text-white border border-transparent rounded-md shadow-sm hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Login
                </button>
            </div>
        </div>

    </div>
</div>
