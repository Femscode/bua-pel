@extends('frontend.master')
@section('header')
<link rel="stylesheet" href="{{ url('homepage/css/contact-us.css') }}" />
@endsection

@section('content')
<div class="solar-breadcrumb">
    <button class="solar-breadcrumb-button">
     <a href="/"> <div class="solar-breadcrumb-item">Home</div></a>
    </button>
    <div class="solar-breadcrumb-divider">/</div>
    <div class="solar-breadcrumb-wrapper">
        <a href='/about'><div class="solar-breadcrumb-item">Contact Us</div></a>
    </div>
</div>


<div class="main-section">
  <h1>Ready to Work With Us?</h1>
  <div class="contact-container">
    <div class="contact-form">
      <p>Contact us for all your questions and opinions</p>
      
      @if(session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
          {{ session('success') }}
        </div>
      @endif
      
      @if($errors->any())
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
          <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form class="form-2" action="{{ route('contact.submit') }}" method="POST">
        @csrf
        <div class="form-row">
          <div class="form-group">
            <label for="first-name">First Name <span class="required">*</span></label>
            <input type="text" id="first-name" name="first_name" required>
          </div>
          <div class="form-group">
            <label for="last-name">Last Name <span class="required">*</span></label>
            <input type="text" id="last-name" name="last_name" required>
          </div>
        </div>
        <div class="form-group">
          <label for="email">Email Address <span class="required">*</span></label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="phone">Phone Number (Optional)</label>
          <input type="tel" id="phone" name="phone">
        </div>
        <div class="form-group">
          <label for="subject">Subject (Optional)</label>
          <input type="text" id="subject" name="subject">
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" placeholder="Note about your order, e.g. special note for delivery" rows="4" required></textarea>
        </div>

        <div class="form-group checkbox-group">
          <input type="checkbox" id="newsletter" name="newsletter">
          <label for="newsletter">I want to receive news and updates on promotions and sales.</label>
        </div>
        <button type="submit" class="send-button">Send Message</button>
      </form>
    </div>
    <div class="contact-info">
      <div class="info-item">
        <h3>Address</h3>
        <p>Somewhere in <br>Lagos, Nigeria</p>
      </div>
      <div class="info-item">
        <h3>Phone</h3>
        <p>+(234) 9076351112<br>+(234) 9168706000</p>
      </div>
      <div class="info-item">
        <h3>Email</h3>
        <p>info@best4uarena.com</p>
      </div>
      <div class="info-item">
        <h3>Business Hours</h3>
        <p>Monday - Friday: 8:00 AM - 5:00 PM<br>Saturday: 9:00 AM - 1:00 PM<br>Sunday: Closed</p>
      </div>
      <!-- <div class="social-links">
        <a href="#" class="social-icon" title="Twitter">&#xf099;</a>
        <a href="#" class="social-icon" title="Facebook">&#xf39e;</a>
        <a href="#" class="social-icon" title="Instagram">&#xf16d;</a>
        <a href="#" class="social-icon" title="YouTube">&#xf167;</a>
        <a href="#" class="social-icon" title="Pinterest">&#xf0d2;</a>
      </div> -->
    </div>
  </div>
  <script>
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('file-upload');
    const uploadButton = document.getElementById('upload-button');
    const filePreview = document.getElementById('file-preview');

    uploadButton.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const files = e.dataTransfer.files;
      handleFiles(files);
    });

    fileInput.addEventListener('change', () => {
      handleFiles(fileInput.files);
    });

    function handleFiles(files) {
      filePreview.innerHTML = '';
      for (const file of files) {
        if (file.type.match(/image\/(png|jpe?g)|application\/pdf/)) {
          const fileName = document.createElement('p');
          fileName.textContent = file.name;
          filePreview.appendChild(fileName);
        }
      }
    }
  </script>
</div>

<div class="main-section">
  <h1>Find Us on Google Map</h1>
  <div class="map-container">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.789356073678!2d3.346977314770672!3d6.599164295233876!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b923a8a1c2b5f%3A0x4e7b8f7b6a2d3e4f!2s81%20Mobolaji%20Bank%20Anthony%20Way%2C%20Ikeja%2C%20Lagos%2C%20Nigeria!5e0!3m2!1sen!2sus!4v1698765432109!5m2!1sen!2sus"
      width="100%"
      height="400"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
    <div class="location-card">
      <h2>Best4UArena</h2>
      <p>Somewhere in</p>
      <p>Lagos, Nigeria</p>
      <div class="rating">
        <span>4.5</span>
        <div class="stars">
          <span class="star">&#9733;</span>
          <span class="star">&#9733;</span>
          <span class="star">&#9733;</span>
          <span class="star">&#9733;</span>
          <span class="star half">&#9733;</span>
        </div>
        <a href="#" class="reviews">944 reviews</a>
      </div>
      <a href="https://www.google.com/maps/dir//81+Mobolaji+Bank+Anthony+Way,+Ikeja,+Lagos,+Nigeria" target="_blank" class="directions">Directions</a>
      <a href="https://www.google.com/maps/place/81+Mobolaji+Bank+Anthony+Way,+Ikeja,+Lagos,+Nigeria" target="_blank" class="larger-map">View larger map</a>
    </div>
  </div>

 
</div>

@endsection

@section('script')
@endsection