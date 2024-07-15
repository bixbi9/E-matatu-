<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="{{ asset('js/dash-script.js') }}" defer></script>
    <link href="{{ asset('css/dash-style.css') }}" rel="stylesheet">
    <title>Admin panel</title>
</head>

<body>
    <!--Top Navbar-->

    <nav class="top-navbar">
        <div>
            <h1><span id="mat">e</span>-Matatu System</h1>
        </div>

        <div id="rightpanel">
            <span class="dot"></span>
            <span class="dot2"></span>
            <span class="dot3"></span>
        </div>
    </nav>

    <!--Left fixed navbar-->

    <div class="main-dashboard">


        <div class="sidebar">
            <section class="system">
                <div class="logo">
                    <h1><span id="mat">e</span>-Matatu system</h1>
                </div>
            </section>

            <ul class="nav_bar">
                <li>
                    <a href={{ asset('/profile')}}>
                        <i class='bx bxs-user'></i>
                        <span class="links_name">My Profile</span>
                    </a>
                    <span class="tool">My Profile</span>
                <li>
                    <a href="#">
                        <i class='bx bxs-dashboard'></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href={{ asset('/crew')}}>
                        <i class='bx bx-money-withdraw'></i>
                        <span class="links_name">Crew</span>
                    </a>
                    <span class="tool">Crew</span>
                </li>

                <li>
                    <a href="{{ asset('/manager')}}">
                        <i class='bx bx-detail'></i>
                        <span class="links_name">Matatu Details</span>
                    </a>
                    <span class="tool">Matatus</span>
                </li>

                <li>
                    <a href="#">
                        <i class='bx bxs-report'></i>
                        <span class="links_name">Reports</span>
                    </a>
                    <span class="tool">Reports</span>
                </li>
                <li id="john_doe">
                    <a href={{ asset('/profile')}}>
                        <i class='bx bxs-user'></i>
                        <span class="links_name">John Doe</span>
                    </a>
                </li>

                <button id="Logout"> <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
                </button>


                    </a>
                </li>
            </ul>
        </div>



        <!--Body content-->

        <div class="main-content">
            <h1>DASHBOARD</h1>
            <br>
            <div class="card-section">
                <div class="card-container one">
                    <h3>Number of trips</h3>
                    <div class="content-in">
                        <div>
                            <h4><a href="#">350+</a></h4>
                        </div>
                        <div>
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                    </div>
                </div>

                <div class="card-container two">
                    <h3>Matatus</h3>
                    <div class="content-in">
                        <div>
                            <h4><a href="#">180</a></h4>
                        </div>
                        <div>
                            <i class="fa-solid fa-bus"></i>
                        </div>
                    </div>
                </div>

                <div class="card-container three">
                    <h3>Crew members</h3>
                    <div class="content-in">
                        <div>
                            <h4><a href="#">300</a></h4>
                        </div>
                        <div>
                            <i class="fa-solid fa-circle-user"></i>
                        </div>
                    </div>
                </div>
                <div class="card-container four">
                    <h3>Total Revenues</h3>
                    <div class="content-in">
                        <div>
                            <h4><a href="#">200+</a></h4>
                        </div>
                        <div>
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>

            <br>
            <br>
            <hr id="line">

            <h1>Analytics</h1>
            <br>
            <br>
            <div class="trips-section">
                <div>
                    <div id="container"></div>
                </div>
                <br>
                <div>
                    <canvas id="myChart"></canvas>
                </div>

                <div id="myChart2"></div>
            </div>
            <div class="more">
                <button class="btn-more"><a href="#">View more...</a></button>
            </div>
            <hr id="line">

            <!--staff list-->
            <div class="list">
                <h1><a href={{ asset('/crew')}}>Staff List</a></h1>
                <br>
                <div class="smp-list">
                    <table id="myTable">
                        <tr class="header">
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Work</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Alfreds</td>
                            <td>+2547123456</td>
                            <td>johndoe@driver.matatu.com</td>
                            <td>Driver</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Alfreds</td>
                            <td>+2547123456</td>
                            <td>johndoe@driver.matatu.com</td>
                            <td>Driver</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Alfreds</td>
                            <td>+2547123456</td>
                            <td>johndoe@driver.matatu.com</td>
                            <td>Driver</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Alfreds</td>
                            <td>+2547123456</td>
                            <td>johndoe@driver.matatu.com</td>
                            <td>Driver</td>
                            <td>Active</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Alfreds</td>
                            <td>+2547123456</td>
                            <td>johndoe@driver.matatu.com</td>
                            <td>Driver</td>
                            <td>Active</td>
                        </tr>

                    </table>
                </div>

                <div class="more">
                    <button class="btn-more"><a href="#">View more...</a></button>
                </div>
<br>

            </div>


        </div>
        </div>
        <div class="footer">
            <p>Author: Shiloh Asiimwe and Allan Kiarie<br>
            <a href="#">e-matatu-system</a></p>
            </div>

</body>

</html>
