<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['check'])){

   $Tgl_Pemesanan = $_POST['Tgl_Pemesanan'];
   $Tgl_Pemesanan = filter_var($Tgl_Pemesanan, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_pemesanan = $conn->prepare("SELECT * FROM `pemesanan` WHERE Tgl_Pemesanan = ?");
   $check_pemesanan->execute([$Tgl_Pemesanan]);

   while($fetch_pemesanan = $check_pemesanan->fetch(PDO::FETCH_ASSOC)){
      $total_jumlah += $fetch_pemesanan['jumlah'];
   }

   // if the hotel has total 30 rooms 
   if($total_jumlah >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{
      $success_msg[] = 'rooms are available';
   }

}

if(isset($_POST['book'])){

   $pemesanan_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $Jenis_Pesanan = $_POST['Jenis_Pesanan'];
   $Jenis_Pesanan = filter_var($Jenis_Pesanan, FILTER_SANITIZE_STRING);
   $jumlah = $_POST['jumlah'];
   $jumlah = filter_var($jumlah, FILTER_SANITIZE_STRING);
   $Tgl_Pemesanan = $_POST['Tgl_Pemesanan'];
   $Tgl_Pemesanan = filter_var($Tgl_Pemesanan, FILTER_SANITIZE_STRING);
   $Tgl_Pengambilan = $_POST['Tgl_Pengambilan'];
   $Tgl_Pengambilan = filter_var($Tgl_Pengambilan, FILTER_SANITIZE_STRING);
   $Tambah_lilin = $_POST['Tambah_lilin'];
   $Tambah_lilin = filter_var($Tambah_lilin, FILTER_SANITIZE_STRING);
   $Tambah_pisau = $_POST['Tambah_pisau'];
   $Tambah_pisau = filter_var($Tambah_pisau, FILTER_SANITIZE_STRING);

   $total_jumlah = 0;

   $check_pemesanan = $conn->prepare("SELECT * FROM `pemesanan` WHERE Tgl_Pemesanan = ?");
   $check_pemesanan->execute([$Tgl_Pemesanan]);

   while($fetch_pemesanan = $check_pemesanan->fetch(PDO::FETCH_ASSOC)){
      $total_jumlah += $fetch_pemesanan['jumlah'];
   }

   if($total_jumlah >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{

      $verify_pemesanan = $conn->prepare("SELECT * FROM `pemesanan` WHERE user_id = ? AND name = ? AND email = ? AND Jenis_Pesanan = ? AND jumlah = ? AND Tgl_Pemesanan = ? AND Tgl_Pengambilan = ? AND Tambah_lilin = ? AND Tambah_pisau = ?");
      $verify_pemesanan->execute([$user_id, $name, $email, $Jenis_Pesanan, $jumlah, $Tgl_Pemesanan, $Tgl_Pengambilan, $Tambah_lilin, $Tambah_pisau]);

      if($verify_pemesanan->rowCount() > 0){
         $warning_msg[] = 'pemesanan Siap!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `pemesanan`(pemesanan_id, user_id, name, email, Jenis_Pesanan, jumlah, Tgl_Pemesanan, Tgl_Pengambilan, Tambah_lilin, Tambah_pisau) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$pemesanan_id, $user_id, $name, $email, $Jenis_Pesanan, $jumlah, $Tgl_Pemesanan, $Tgl_Pengambilan, $Tambah_lilin, $Tambah_pisau]);
         $success_msg[] = 'pemesanan succes!';
      }

   }

}

if(isset($_POST['send'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $Jenis_Pesanan = $_POST['Jenis_Pesanan'];
   $Jenis_Pesanan = filter_var($Jenis_Pesanan, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND Jenis_Pesanan = ? AND message = ?");
   $verify_message->execute([$name, $email, $Jenis_Pesanan, $message]);

   if($verify_message->rowCount() > 0){
      $warning_msg[] = 'message siap!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, Jenis_Pesanan, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $Jenis_Pesanan, $message]);
      $success_msg[] = 'Pesan Terkirim!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- home section starts  -->

<section class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="box swiper-slide">
            <img src="images/1.jpeg" alt="">
            <div class="flex">
               <h3>Peach Cake</h3>
               <a href="#reservation" class="btn">Lanjut ke Pemesanan</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/2.jpg" alt="">
            <div class="flex">
               <h3>Chocolate Cake</h3>
               <a href="#reservation" class="btn">Lanjut ke Pemesanan</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/3.jpeg" alt="">
            <div class="flex">
               <h3>Chocolate Cake</h3>
               <a href="#reservation" class="btn">Lanjut ke Pemesanan</a>
            </div>
         </div>

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<!-- home section ends -->

<!-- about section starts  -->

<section class="about" id="about">

   <div class="row">
      <div class="image">
         <img src="images/about1.jpg" alt="">
      </div>
      <div class="content">
         <h3>Choco Chip Cookies </h3>
         <p>Rp.45.000- <span>60.000</span></p>
         <p>Kue kering yang satu ini sangat cocok menjadi teman minum teh atau susu. Sensasi renyah choco chip cookies dengan butiran cokelat di dalamnya membuat kue ini semakin istimewa. </p>
         <a href="#reservation" class="btn">Buat Pesanan</a>
      </div>
   </div>

   <div class="row revers">
      <div class="image">
         <img src="images/about2.jpg" alt="">
      </div>
      <div class="content">
         <h3>Chocolate Cake</h3>
         <p>Rp.55.000- <span>100.000</span></p>
         <p>Cake ini sangat moist dengan tekstur yang lembut. Haa, tidak heran, berton-ton coklat dan mentega terkandung di dalamnya. Memang bukan untuk yang sedang berdiet karena kalorinya yang pasti selangit. Tapi bagi penggemar coklat umumnya dan cake coklat khususnya, cake ini bisa menjadi pilihan.</p>
         <a href="#reservation" class="btn">Buat Pesanan</a>
      </div>
   </div>

   <div class="row">
      <div class="image">
         <img src="images/about3.jpg" alt="">
      </div>
      <div class="content">
         <h3>Birthday cake</h3>
         <p>Rp.50.000- <span>110.000</span></p>
         <p>Kue sering dianggap sebagai salah satu sajian yang cocok dan pas karena desainnya yang cukup beragam dan sangat menarik sekali. Memakan kue juga tidak akan membuat kenyang berlebih, jadi kita bisa ikut merayakan pesta bersama teman dan yang lainnya.</p>
         <a href="#reservation" class="btn">Buat Pesanan</a>
      </div>
   </div>

   <div class="row revers">
      <div class="image">
         <img src="images/about4.jpg" alt="">
      </div>
      <div class="content">
         <h3>Red Velvet Cake</h3>
         <p>Rp.65.000- <span>150.000</span></p>
         <p>Cake ini sangat moist dengan tekstur yang lembut. Haa, tidak heran, berton-ton coklat dan mentega terkandung di dalamnya. Memang bukan untuk yang sedang berdiet karena kalorinya yang pasti selangit. Tapi bagi penggemar coklat umumnya dan cake coklat khususnya, cake ini bisa menjadi pilihan.</p>
         <a href="#reservation" class="btn">Buat Pesanan</a>
      </div>
   </div>

   <div class="row">
      <div class="image">
         <img src="images/about5.jpg" alt="">
      </div>
      <div class="content">
         <h3>Strawberry Cream Cake</h3>
         <p>Rp.55.000- <span>150.000</span></p>
         <p>Kue sering dianggap sebagai salah satu sajian yang cocok dan pas karena desainnya yang cukup beragam dan sangat menarik sekali. Memakan kue juga tidak akan membuat kenyang berlebih, jadi kita bisa ikut merayakan pesta bersama teman dan yang lainnya.</p>
         <a href="#reservation" class="btn">Buat Pesanan</a>
      </div>
   </div>

</section>

<!-- about section ends -->

<!-- services section starts  -->

<section class="services">

   <div class="box-container">

      <div class="box">
         <img src="images/icon1.jpg" alt="">
         <p>Rp.35.000- <span>60.000</span></p>
         <h3>Black Forest Cake</h3>
         <p>Kue yang didominasi oleh cokelat lezat ini bisa dengan mudah dijumpai dan menjadi favorit banyak orang.</p>
      </div>

      <div class="box">
         <img src="images/icon2.jpg" alt="">
         <p>Rp.55.000- <span>60.000</span></p>
         <h3>Black Forest Pastry</h3>
         <p>Kue hutan hitam tanpa telur yang sangat kaya, lembut, dan lembap, dengan dua lapis kue cokelat yang diresapi ceri dan frosting krim kocok yang lezat, adalah kombinasi surgawi yang merupakan harta karun untuk disayangi! </p>
      </div>

      <div class="box">
         <img src="images/icon3.jpg" alt="">
         <p>Rp.55.000- <span>80.000</span></p>
         <h3>Birthday Cake Cookies</h3>
         <p>Terkadang, ulang tahun membutuhkan kue dan kue. Dipenuhi dengan rasa yang mengingatkan pada kue kuning klasik dengan frosting buttercream, kue kue ulang tahun ini akan menjadi hit di pesta.</p>
      </div>

      <div class="box">
         <img src="images/icon4.jpg" alt="">
         <p>Rp.50.000- <span>150.000</span></p>
         <h3>Birthday Cake Cookies</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon5.jpg" alt="">
         <p>Rp.30.000- <span>95.000</span></p>
         <h3>Cookies</h3>
         <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p>
      </div>

      <div class="box">
         <img src="images/icon6.jpg" alt="">
         <p>Rp.35.000- <span>90.000</span></p>
         <h3>Chocolate Chip Cookies</h3>
         <p>Dengan spons keping cokelat yang lembut, frosting krim mentega meringue Swiss yang lembut, tetesan ganache cokelat yang lezat, dan kue keping cokelat yang melimpah… tidak ada yang tidak disukai dari kue ini.</p>
      </div>

   </div>

</section>

<!-- services section ends -->

<!-- reservation section starts  -->

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>Isi Data Pemesanan</h3>
      <div class="flex">
         <div class="box">
            <p>Nama <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="masukkan nama" class="input">
         </div>
         <div class="box">
            <p>Email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="masukkan email" class="input">
         </div>
         <div class="box">
            <p>Jenis Pesanan <span>*</span></p>
            <input type="Jenis_Pesanan" name="Jenis_Pesanan" maxlength="50" required placeholder="masukkan jenis pesanan" class="input">
         </div>
         <div class="box">
            <p>Jumlah Pesanan <span>*</span></p>
            <select name="jumlah" class="input" required>
               <option value="1" selected>1 </option>
               <option value="2">2 </option>
               <option value="3">3 </option>
               <option value="4">4 </option>
               <option value="5">5 </option>
               <option value="6">6 </option>
            </select>
         </div>
         <div class="box">
            <p>Tgl Pemesanan <span>*</span></p>
            <input type="date" name="Tgl_Pemesanan" class="input" required>
         </div>
         <div class="box">
            <p>Tgl Pengambilan <span>*</span></p>
            <input type="date" name="Tgl_Pengambilan" class="input" required>
         </div>
         <div class="box">
            <p>Tambah Lilin <span>*</span></p>
            <select name="Tambah_lilin" class="input" required>
               <option value="0" selected>0 </option>
               <option value="1">1 </option>
               <option value="2">2 </option>
               <option value="3">3 </option>
               <option value="4">4 </option>
               <option value="5">5 </option>
               <option value="6">6 </option>
            </select>
         </div>
         <div class="box">
            <p>Tambah Pisau <span>*</span></p>
            <select name="Tambah_pisau" class="input" required>
               <option value="0" selected>0 </option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
               <option value="6">6</option>
            </select>
         </div>
      </div>
      <input type="submit" value="Kirim" name="book" class="btn">
   </form>

</section>

<!-- reservation section ends -->

<!-- gallery section starts  -->

<section class="gallery" id="gallery">

   <div class="swiper gallery-slider">
      <div class="swiper-wrapper">
         <img src="images/gallery1.jpg" class="swiper-slide" alt="">
         <img src="images/gallery2.jpg" class="swiper-slide" alt="">
         <img src="images/gallery3.jpg" class="swiper-slide" alt="">
         <img src="images/gallery4.jpg" class="swiper-slide" alt="">
         <img src="images/gallery5.jpg" class="swiper-slide" alt="">
         <img src="images/gallery6.jpg" class="swiper-slide" alt="">
      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- gallery section ends -->

<!-- contact section starts  -->
<section class="contact" id="contact">

   <div class="row">

      <form action="" method="post">
         <h3>Kirim Pesan</h3>
         <input type="text" name="name" required maxlength="50" placeholder="masukkan nama" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="masukkan email" class="box">
         <input type="Jenis_Pesanan" name="Jenis_Pesanan" required maxlength="50" placeholder="masukkan jenis pesanan" class="box">
         <textarea name="message" class="box" required maxlength="1000" placeholder="tuliskan pesan" cols="30" rows="10"></textarea>
         <input type="submit" value="Kirim" name="send" class="btn">
      </form>

      <div class="faq">
         <h3 class="title">Cake & Cookies</h3>
         <div class="box active">
            <h3>Cake</h3>
            <p>Cake adalah produk makanan semi basah yang dibuat dengan pemanggangan adonan yang terdiri dari tepung terigu, gula, telur, susu, lemak, dan bahan pengembang dengan atau tanpa penambahan bahan tambahan makanan lain yang diizinkan.</p>
         </div>
         <div class="box">
            <h3>Cookies</h3>
            <p>Cookies adalah kue yang terbuat dari bahan dasar tepung yang umumnya dibuat dari tepung terigu, gula halus, telur ayam, vanilli, margarine, tepung maizena, baking powder, dan susu bubuk instant. Tekstur cookies mempunyai tekstur yang renyah dan tidak mudah hancur seperti dengan kue-kue kering pada umumnya.</p>
         </div>
         <div class="box">
            <h3>Jenis-Jenis Cake</h3>
            <p>1. Batter type cake/pound cake/convensional cake</p>
            <p>2. Foam type cake sering juga disebut sponge cake</p>
            <p>3. Chiffon type cake</p>

         </div>
         <div class="box">
            <h3>Jenis-Jenis Cookies</h3>
            <p>1. Chocolate atau Choco Chips</p>
            <p>2. Rolled Cookies</p>
            <p>3. Bar Cookies</p>
         </div> 
   </div>

</section>

<!-- contact section ends -->

<!-- reviews section starts  -->

<section class="reviews" id="reviews">

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">
         <div class="swiper-slide box">
            <img src="images/pic-1.jpg" alt="">
            <h3>Sukacita Shull</h3>
            <p>Kue basah tradisional dengan kualitas rasa premium!</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-2.png" alt="">
            <h3>Ana</h3>
            <p>Anda menggunakan minyak zaitun. Apakah bisa menggunakan minyak kanola?Terima kasih</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-3.png" alt="">
            <h3>Aan</h3>
            <p>Ya!</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-4.png" alt="">
            <h3>Nita</h3>
            <p>Kue kue ditoko ini enak dan mantap mantap rasanya</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-5.png" alt="">
            <h3>Edo</h3>
            <p>Ukurannya juga cukup besar jadi nikmat sekali untuk dijadikan camilan.</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/pic-6.png" alt="">
            <h3>Ani</h3>
            <p>saya senang sekali berbelanja terutama kue kue kecil yang dipaket satu bundel plastik, top lah coba rasanya</p>
         </div>
      </div>

      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- reviews section ends  -->





<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>