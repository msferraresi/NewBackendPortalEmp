@if(Session::has('notificacion'))
            <div class="alert toast toast-{{ Session::get('notificacion.alert-type') }} toast-top-center text-inner" style="color: #fff;"  id="toast-container">
                <label for="">{{ strtoupper(Session::get('notificacion.message')) }}</label>
            </div>

            @endif