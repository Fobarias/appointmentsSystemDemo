<!DOCTYPE html>
<?php

$businessName = "test";

?>


<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="static/css/tailwind.css">
    <link rel="stylesheet" href="static/css/selectWithSearch.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <title><?php echo $businessName; ?> | Appointment</title>
</head>

<body class="bg-[#2C2C2C] dark:text-white overflow-hidden">
    <div class="w-full h-screen bg-center bg-cover" style="background: url('static/img/barbershopBackground.jpg');">
        <div class="h-[57px] bg-[#2C2C2C] w-full border-b-2 border-white flex justify-center items-center">
            <p class="text-3xl font-semibold"><?php echo $businessName; ?></p>
        </div>
        <div class="flex items-center justify-center mt-10 md:grid-cols-3 md:grid">
            <section></section>
            <div class="flex items-center justify-center h-full col-span-2">
                <section class="h-full bg-[#2C2C2C] rounded-md flex justify-center items-center px-10 w-[70%] lg:h-[600px] ">
                    <div class="w-full h-auto grid-rows-3 mt-10 mb-10 grd">
                        <div>
                            <div class="flex items-center justify-center w-full">
                                <select class="selectSearch">
                                    <option value="1">Select employee</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-center w-full mt-3">
                                <select class="selectSearch">
                                    <option value="1">Select service</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="text" placeholder="Name" class="block w-[95%] mt-2 ml-auto mr-auto bg-transparent border-t-0 border-b-2 border-l-0 border-r-0 border-white">
                            <input type="text" placeholder="Phone number" class="block w-[95%] mt-2 ml-auto mr-auto bg-transparent border-t-0 border-b-2 border-l-0 border-r-0 border-white">
                            <input type="text" placeholder="Email" class="block w-[95%] mt-2 ml-auto mr-auto bg-transparent border-t-0 border-b-2 border-l-0 border-r-0 border-white">
                            <input type="text" placeholder="Address" class="block w-[95%] mt-2 ml-auto mr-auto bg-transparent border-t-0 border-b-2 border-l-0 border-r-0 border-white">
                        </div>
                        <div class="sm:grid sm:grid-cols-2">
                            <div class="flex items-center justify-center">
                                <p class="relative bottom-0 mt-3 text-sm font-semibold text-center">©</p>
                            </div>
                            <input type="submit" value="Make appointment" class="block px-3 py-1 mt-5 ml-auto mr-auto duration-100 border-2 border-white rounded-md hover:text-black hover:bg-white">
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="static/js/selectWithSearch.js"></script>
</body>

</html>