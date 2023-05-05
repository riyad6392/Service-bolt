<!-- <section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-p">
                    <p>{{$errors}}</p>
                </div>
            </div>
            
        </div>
    </div>
</section>
 -->
<style type="text/css">
    

body{ background: #6BA1CA; }

.error-500{
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  font-family: 'VT323';
  color: #1E4B6D;
  text-shadow: 1px 1px 1px rgba(255, 255, 255, .3);
}

.error-500:after{
  content: attr(data-text);
  display: block;
  margin-top: calc(var(--height) / 10 + 15px);
  font-size: 28pt;
  text-align: center;
}

.back-btn {
    display: flex;
    margin-top: 15px;
    /*color: #232322;*/
    text-decoration: none;
    align-items: center;
}

</style>
<div class="container">

    <div class="error-500" data-text="{{$errors}}"><h2><a href="{{ url()->previous() }}" class="back-btn"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" fill="currentColor" d="M10.78 19.03a.75.75 0 01-1.06 0l-6.25-6.25a.75.75 0 010-1.06l6.25-6.25a.75.75 0 111.06 1.06L5.81 11.5h14.44a.75.75 0 010 1.5H5.81l4.97 4.97a.75.75 0 010 1.06z"></path></svg>Back</a></h2></div>
</div>


