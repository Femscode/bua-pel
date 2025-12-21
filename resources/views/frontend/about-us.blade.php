@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/about-us.css') }}" />
@endsection

@section('content')
<div class="solar-breadcrumb">
  <button class="solar-breadcrumb-button">
    <a href="/">
      <div class="solar-breadcrumb-item">Home</div>
    </a>
  </button>
  <div class="solar-breadcrumb-divider">/</div>
  <div class="solar-breadcrumb-wrapper">
    <a href='/about'>
      <div class="solar-breadcrumb-item">About Us</div>
    </a>
  </div>
</div>

<div class="main-section">
  <div class="banner">
    <h1>About Best4UArena</h1>
    <p class="subtitle">Fashion & Lifestyle Store</p>
  </div>
  
  <div class="about-content-wrapper">
      <h3 class="tagline">BRINGING<span class="highlight"> THE LATEST TRENDS </span>TO YOU</h3>
      
      <div class="info-section">
        <div class="info-card">
          <p class="intro-text">Best4UArena is your trusted partner in modern fashion and lifestyle.</p>
          <p>We started our journey in 2020 and we are committed to providing the best quality products at affordable prices. Our collection includes a wide range of fashion items, accessories, and beauty products.</p>
          <div class="shop-now-wrapper">
            <a href="/shop" class="shop-now">Shop Now</a>
          </div>
        </div>
      </div>

      <div class="values">
        <div class="value-card">
          <div class="value-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.42 4.58a5.4 5.4 0 0 0-7.65 0l-.77.78-.77-.78a5.4 5.4 0 0 0-7.65 0C1.46 6.7 1.33 10.28 4 13l8 8 8-8c2.67-2.72 2.54-6.3.42-8.42z"></path></svg>
          </div>
          <h2>Customer First</h2>
          <p>We place your style and satisfaction at the heart of everything we do.</p>
        </div>
        <div class="value-card">
          <div class="value-icon">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
          </div>
          <h2>Trendsetters</h2>
          <p>Our vision is always forward-looking—bringing you the latest styles and trends.</p>
        </div>
        <div class="value-card">
          <div class="value-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
          </div>
          <h2>Quality Assured</h2>
          <p>Quality, integrity, and service excellence form the backbone of our operations.</p>
        </div>
      </div>

      <div class="vision-mission">
        <div class="content-block policy-block">
            <h2>Our Policy</h2>
            <ul class="policy-list">
              <li><strong>Shipping:</strong> Orders are processed within 1-2 business days.</li>
              <li><strong>Returns:</strong> You may return items within 7 days of receipt if they are unused and in original packaging.</li>
              <li><strong>Refunds:</strong> Approved refunds are processed within 3-5 business days.</li>
              <li><strong>Privacy:</strong> We respect your privacy and never share your personal data with third parties.</li>
            </ul>
            <p class="policy-note">For more details, please contact our support team.</p>
        </div>
        
        <div class="mission-vision-wrapper">
            <div class="content-block">
                <h2>Our Mission</h2>
                <p>Our mission is to empower individuals to express themselves through style, providing affordable and high-quality fashion solutions.</p>
            </div>
            <div class="content-block">
                <h2>Our Vision</h2>
                <p>To be a leading destination for fashion and beauty, inspiring confidence and style in every customer.</p>
            </div>
        </div>
      </div>
  </div>

  <!-- <div class="team">
    <h2>Our Team</h2>
    <div class="team-members">
      <div class="team-member">
        <div class="member-image" style="background-image: url('homepage/images/about/ceo1.png');"></div>
        <h3>Yetunde</h3>
        <p>Chief Executive Officer</p>
      </div>
      <div class="team-member">
        <div class="member-image" style="background-image: url('homepage/images/about/ceo2.png');"></div>
        <h3>Bayo</h3>
        <p>Head of Operations</p>
      </div>
      <div class="team-member">
        <div class="member-image" style="background-image: url('homepage/images/about/adeoye.jpg');"></div>
        <h3>Laitan</h3>
        <p>Fashion Director</p>
      </div>
      <div class="team-member">
        <div class="member-image" style="background-image: url('homepage/images/about/pelumi.png');"></div>
        <h3>Pelumi</h3>
        <p>Digital Marketing Manager</p>
      </div>
    </div>
  </div> -->


</div>

@endsection

@section('script')
@endsection
