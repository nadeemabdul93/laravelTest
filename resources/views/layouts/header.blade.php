

<header id="topnav" class="defaultscroll sticky">

    <div class="container">

        <a class="logo" href="/">

            <img src="{{asset('images/dream maker logo.svg')}}" class="logo-light-mode" alt="">

        </a>



        <div class="menu-extras">

            <div class="menu-item">

                <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">

                    <div class="lines">

                        <span></span>

                        <span></span>

                        <span></span>

                    </div>

                </a>

            </div>

        </div>



        <ul class="buy-button list-inline mb-0">

        </ul>



        <div id="navigation">

            <ul class="navigation-menu">

                

               

                <li class="sub-menu-item">

               
                            <a href="{{route('files')}}">Uploaded files</a>
                        </li>
                        <li class="sub-menu-item">       
                            <a href="{{route('upload-csv')}}">Upload Csv</a>

                       
                </li>

                

                

               
            </ul>

        </div>

        

    </div>

</header>