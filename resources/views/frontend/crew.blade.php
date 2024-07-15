change this code to return
<!-- PHP code to establish connection with the localserver -->
<?php

// Username is root
$user = 'root';
$password = '';

// Database name is geeksforgeeks
$database = 'IT_Project';

// Server is localhost with
// port number 3306
$servername = 'localhost:3306';
$mysqli = new mysqli($servername, $user,
    $password, $database);

// Checking for connections
if ($mysqli->connect_error) {
    die('Connect Error (' .
        $mysqli->connect_errno . ') ' .
        $mysqli->connect_error);
}

// SQL query to select data from database
$sql = " SELECT * FROM drivers ORDER BY driver_id DESC ";
$result = $mysqli->query($sql);
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/crew-style2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dash-style.css') }}" rel="stylesheet">

    <title>Admin panel</title>
</head>

<body>
    <!--Top Navbar-->

    <nav class="top-navbar">
        <div>
            <h1>E-MATATU SYSTEM</h1>
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
                    <h1><span id="mat">e-</span>Matatu system</h1>
                </div>
            </section>

            <ul class="nav_bar">
                <li>
                    <a href={{ asset('/profile')}}>
                        <i class='bx bxs-user'></i>
                        <span class="links_name">My Profile</span>
                    </a>
                <li>
                    <a href={{ asset('/adminpanel')}}>
                        <i class='bx bxs-dashboard'></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tool">Dashboard</span>
                </li>

                <li>
                <a href={{ asset('/crew')}}>
                        <i class='bx bx-money-withdraw'></i>
                        <span class="links_name">Crew</span>
                    </a>
                    <span class="tool">Crew</span>
                </li>

                <li>
                <a href={{ asset('/manager')}}>
                        <i class='bx bx-detail'></i>
                        <span class="links_name">Matatu Details</span>
                    </a>
                    <span class="tool">Matatu Details</span>
                </li>

                <li>
                    <a href="#">
                        <i class='bx bxs-report'></i>
                        <span class="links_name">Reports</span>
                    </a>
                    <span class="tool">Reports</span>
                </li>
            </ul>

            <ul class="nav_bar">
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
            </ul>
        </div>
        <div class="main-content">
            <h1>Crew Members</h1>
            <br>
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.."
                title="Type in a name">
            <br>
            <table id = 'myTable'>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>License number</th>
                <th>Phone number</th>
                <th>Password</th>
                <th>Status</th>'
        '       <th>Comments</th>
                <th>Role id</th>
             </tr>
        <tbody>
          <!-- PHP CODE TO FETCH DATA FROM ROWS -->
          <?php
// LOOP TILL END OF DATA
while ($rows = $result->fetch_assoc()) {
    ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
                <td><?php echo $rows['first_name']; ?></td>
                <td><?php echo $rows['last_name']; ?></td>
                <td><?php echo $rows['license_number']; ?></td>
                <td><?php echo $rows['phone_number']; ?></td>
                <td><?php echo $rows['password']; ?></td>
                <td><?php echo $rows['status']; ?></td>
                <td><?php echo $rows['comments']; ?></td>
                <td><?php echo $rows['role_id']; ?></td>
            </tr>
            </tbody>
                <?php
}
?>
                    <button class="btn-list" onclick="document.getElementById('NewMemberForm').style.display='block'"
                    style="width:60%;">+ Add New Crew Member</button>
            <br>
            <br>
        <div id="NewMemberForm" class="modal" >
        <form action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <div class="container" style="background-color:#f1f1f1">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="license_number">License Number:</label>
                <input type="number" class="form-control" id="license_number" name="license_number" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="text" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <input type="text" class="form-control" id="status" name="status" required>
            </div>
            <div class="form-group">
                <label for="comments">Comments:</label>
                <input type="text" class="form-control" id="comments" name="comments">
            </div>
            <div class="form-group">
                <label for="role_id">Role ID:</label>
                <input type="number" class="form-control" id="role_id" name="role_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <div class="container" style="background-color:#f1f1f1">
             <button type="button" onclick="document.getElementById('NewMemberForm').style.display='none'"
                            class="cancelbtn">Cancel</button>
                    </div>
        </form>
        </div>



                    <!-- <div class="container">
                        <label for="uname"><b>Full Name</b></label>
                        <input type="text" placeholder="Enter full name" name="uname" id="uname" required>
                        <label for="idnum"><b>I.D No.</b></label>
                        <input type="number" placeholder="Enter ID Number" name="idnum" id="idnum" required>
                        <label for="dl"><b>Driver's License</b></label>
                        <input type="number" placeholder="Enter Driver's License" name="dl" required>
                        <label for="email"><b>Email</b></label>
                        <input type="email" placeholder="Enter Email" name="email" id="email" required>
                        <label for="contnum"><b>Contact Number</b></label>
                        <input type="number" placeholder="Enter Contact Number" name="contnum" id="contnum" required>

                        <label for="dob"><b>D.O.B</b></label>
                        <input type="date" placeholder="Enter Date of Birth" name="dob" required>
                        <p><b>Please select Gender:</b></p> <br>
                        <input type="radio" id="html" name="fav_language" value="HTML">
                        <label for="html">Male</label><br>
                        <input type="radio" id="css" name="fav_language" value="CSS">
                        <label for="css">Female</label> <br><br>
                        <p><b>Upload documents</b></p><br>
                        <input type="file" id="myFile" name="filename">
                        <br> <br>
                        <button type="submit">Add</button>
                    </div>

                    <div class="container" style="background-color:#f1f1f1">
                        <button type="button" onclick="document.getElementById('id01').style.display='none'"
                            class="cancelbtn">Cancel</button>
                    </div>
                </form>
            </div> -->

            <br>
            <br>
            <h1>New users</h1>
        </div>
    </div>
    <script src="./js/index.js"></script>
    <script>
        function myFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Get the modal
        var modal = document.getElementById('id01');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        //new user function

        function add() {

            function Person(uname, idnum, email) {
                this.uname = uname,
                    this.idnum = idnum,
                    this.email = email
            };

            var people = new Array();

            var uname = document.getElementById('uname').value;
            var idnum = document.getElementById('idnum').value;
            var email = document.getElementById('email').value;

            if (uname.length === 0 || idnum.length === 0 || email.length === 0) {
                alert("Enter fields");
                return;
            } else {
                var person = new Person(uname, idnum, email);
                people[people.length] = person;

                drawTable(people);
            }
        };


    </script>

    <table>
        <script type="text/javascript">

           function drawTable(people){
                for (var i = 0; i < people.length; i++) {
                    document.write("<tr><td>" + i.uname + "</td><td>" + i.idnum + "</td><td>" + i.email + "</td></tr>");
                }
            }

        </script>

    </table>

</body>