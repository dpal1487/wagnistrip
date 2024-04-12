<div class="overlay"></div>
<header class="header">
    @php
        use App\Models\Agent\Agent;
        $Agent = Session()->get('Agent');
    @endphp
    <nav class="header-inner" id="navbar">
        <div class="container commonFlex alignItems contentBetween">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
            </a>
            <ul class="navMenu commonFlex alignItems">

                <li class="nav-item">
                    <a class="nav-link activeLink" href="https://www.flights.wagnistrip.com/"> <i
                            class="fa fa-plane"></i> Flights</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="/hotels"> <i class="fa fa-building-o"></i> Hotels
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/holidays"> <i class="fa fa-yelp"></i>
                        Holidays</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/events"> <i class="fa fa-calendar-o"></i>
                        Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/visa"> <i class="fa fa-book"></i> Visa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle effects" id="moreBTN" data-toggle="dropdown"
                        aria-expanded="false">More</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item effects" href="/customer-support">Customer
                            Care</a>
                        <a class="dropdown-item effects" href="/travel-insurance">Travel
                            Insurance</a>
                    </div>
                </li>
                @if ($Agent != null)
                    <!--<li class="nav-item"><a class="nav-link" href="{{ url('/group-fare') }}"> <i class="fa fa-users"></i>Group Fare</a></li>-->
                @endif
            </ul>
            <div class="userDropDown dropdown">
                <button type="button" onclick="ChkIsLogin()" class="userAccBtn openDrp dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-user"></i> My Account</button>
                <div class="dropdown-menu">
                    <div id="afterLoginContent">
                        @if ($Agent != null)
                            @php
                                $mail = $Agent->email;
                                $Agent = Agent::where('email', '=', $mail)->first();
                            @endphp
                            <a class="dropdown-item" href="https://www.flights.wagnistrip.com/Agent/Dashboard"> <i
                                    class="fa fa-tachometer"></i>Patner Dashboard</a>
                            <a class="dropdown-item" href="javascript:void(0)"> <i class="fa fa-tachometer"></i>
                                Fund {{ $Agent->state }} </a>
                            <a class="dropdown-item" href="https://www.flights.wagnistrip.com/Agent/LogOut"> <i
                                    class="fa fa-sign-out"> </i> Logout </a>
                        @else
                            <div id="flightLoginBtncc"></div>
                            <div id="afterLoginContentcc"></div>
                            <a class="dropdown-item" href="https://www.flights.wagnistrip.com/Agent/login"> <i
                                    class="fa fa-sign-out"></i> Patner Login or singup </a>
                        @endif
                    </div>
                </div>
            </div>
           
            <div class="menuIcon">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:svgjs="http://svgjs.com/svgjs" width="45" height="45" x="0" y="0" viewBox="0 0 16 16"
                    style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <g data-name="31">
                            <path
                                d="M15.5 4H.5a.5.5 0 0 1 0-1h15a.5.5 0 0 1 0 1zM15.5 9H.5a.5.5 0 0 1 0-1h15a.5.5 0 0 1 0 1zM15.5 14H.5a.5.5 0 0 1 0-1h15a.5.5 0 0 1 0 1z"
                                fill="#0164a3" data-original="#000000" class=""></path>
                        </g>
                    </g>
                </svg>
            </div>
        </div>
    </nav>
</header>
@php
    $er = '';
@endphp
@if (isset($error))
    @php
        $er = $error;
    @endphp
@endif

<!-- Modal -->
<div style="z-index:1000000;" class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Login / Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="boxss">
                    <h1>Login / Register</h1>
                    <form method="Post" action="{{ url('/') }}/api/logins" id="userLoginSignInForm">
                        @csrf
                        <div class="mb-3 groupss">
                            <input type="text" class="inputss" id="email" name="email"
                                placeholder="Email or Mobile Number" required>
                            <label for="email" class="form-label email">Email or Mobile Number</label>
                        </div>
                        <p class="errss ">{{ $er }}</p>
                        <button type="submit" class="btn btn-primary w-100 p-2">CONTINUE</button>
                    </form>
                    <article>
                        <p class="borss">Login/Register</p>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // forEach(link => {
    //     if (!link.href.includes(`${activePage}`)) {        
    //         link.classList.remove("act");
    //     }
    //     else {
    //         if (!('/' == activePage)) {
    //             link.classList.add("act");
    //         }
    //     }
    // });



    function ChkIsLogin() {
        let url = '/api/isLogin';
        // console.log(url);
        fetch(url, {
                method: 'GET',
            }).then(res => res.json())
            .then((res) => {
                if (res.status === true) {
                    document.getElementById("afterLoginContentcc").innerHTML =
                        `<a class="dropdown-item" href="https://www.wagnistrip.com/user/dashboard"> <i class="fa fa-user-circle-o"></i> Profile </a>
                        <a class="dropdown-item" href="https://www.wagnistrip.com/user-dashboard"> <i class="fa fa-tachometer"></i> Dashboard </a>
                        <a class="dropdown-item" href="https://www.wagnistrip.com/login" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Logout </a>`;
                } else if (res.status === false) {
                    document.getElementById("flightLoginBtncc").innerHTML =
                        `<button type="button" class="btn btn-primary dropdown-item"  data-toggle="modal" data-target="#staticBackdrop">User Login | Sign Up</button>`;
                }
            }).catch((error) => console.log(error));
    }


    $('.menuIcon').click(function() {
        $('.navMenu').addClass('active');
        $('.overlay').addClass('active');
    });
    $('.overlay').click(function() {
        $('.navMenu').removeClass('active');
        $('.overlay').removeClass('active');
    });


    // let userLoginFunc = () =>{
    //     let userLoginForm = document.getElementByID("userLoginSignInForm");
    //     userLoginForm.addEventListener('submit', (e) =>{
    //         e.preventDefault();
    //         let formData = new formData(userLoginForm);
    //         let PlainFormData = Object.fromEntries(formData.entries());
    //         let formDataJsonString = JSON.stringify(PlainFormData);
    //         let url = 'https://www.wagnistrip.com/api/IsLogin';
    //         fetch(url, {
    //             method: "Post",
    //             headers: {
    //                 "Content-Type": "application/json"
    //             },
    //             body: formDataJsonString
    //         }).then(res = res.json())
    //         .then((res)=>{
    //             if(res.status == true){
    //                 $("#staticBackdrop").modal("hide");
    //                 $("#staticBackdropOTP").modal("show");
    //             }else {
    //                 alert("unknown situation");
    //             }
    //         }).catch((error) => console.log(error));
    //     });
    // }
    // userLoginFunc();
</script>
