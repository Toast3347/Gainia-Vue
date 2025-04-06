<script>
</script>
<template>
  <div class="page contact container py-5">
    <h1 class="display-4 fw-bold text-center mb-4">Contact Us</h1>
    <p class="lead text-center mb-5">
      Have questions or need assistance? Feel free to reach out to us using the form below or through our contact details.
    </p>
    <p>This is a demo page. The contact form does not send any data.</p>

    <div class="row">
      <div class="col-md-6">
        <h2 class="h4 mb-3">Send Us a Message</h2>
        <form>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" placeholder="Your Name" />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Your Email e.g: support@gainia.com" />
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" rows="4" placeholder="Your Message"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>

      <div class="col-md-6">
        <h2 class="h4 mb-3">Our Contact Details</h2>
        <ul class="list-unstyled">
          <li class="mb-2">
            <strong>Address:</strong> 123 Gainia Street, Fitness City, 56789, Netherlands
          </li>
          <li class="mb-2">
            <strong>Email:</strong> <a href="mailto:support@gainia.com">support@gainia.com</a>
          </li>
          <li class="mb-2">
            <strong>Phone:</strong> +31 (010) 123-4567
          </li>
          <li>
            <strong>Working Hours:</strong> Mon - Fri, 9:00 AM - 5:00 PM
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped>


i {
  transition: transform 0.2s ease;
}

i:hover {
  transform: scale(1.2);
}
</style>