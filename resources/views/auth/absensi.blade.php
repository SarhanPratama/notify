@include('layouts.link')
@php
    $title = 'Login';
@endphp
<title>Seroo - {{ $title }}</title>
<style>
    .text-maron {
        color: #861414;
    }

    .bg-maron {
        background-color: #861414;
    }

    .video-container {
        text-align: center;
        margin-top: 20px;
    }

    #videoElement {
        width: 100%;
        max-width: 500px;
        height: auto;
    }

    .btn-camera {
        background-color: #861414;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        margin-top: 20px;
    }
</style>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<main class="main-content">
    <section class="vh-100" style="background-color: #f8f9fa;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-4">
                    <div class="card text-light shadow-lg bg-maron">
                        <div class="card-header">
                            <img src="{{ asset('assets/img/logo/brand.png') }}" alt="Logo"
                                class="card-img-top mx-auto w-25">
                        </div>
                        <div class="card-body px-3">
                            <div class="text-center">
                                <label class="card-title" style="font-size: 20px; font-weight:bold;">
                                    <p>Login</p>
                                </label>
                                @if($errors->any())
                                <div class="alert alert-light alert-dismissible fade show d-flex align-content-center"
                                    role="alert">
                                    <i class="bi bi-exclamation-circle me-2 text-maron"></i>
                                    <p class="text-sm">Email atau Password tidak valid.</p>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <form action="{{ route('login') }}" method="POST" class="text-sm">
                                @csrf
                                <div class="mb-3 font-weight-bold">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" name="email"
                                        placeholder="Email" aria-label="email" required>
                                </div>

                                <div class="mb-4 font-weight-bold">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control form-control-sm" name="password"
                                        placeholder="Password" aria-label="password" required>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-sm btn-outline-light w-100 text-center mb-3">
                                    <strong>Login</strong>
                                </button>

                                <div class="card-footer d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('register') }}" class="text-light text-decoration-underline fw-bold">
                                            Create Account
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('password.request') }}" class="text-decoration-underline text-light fw-bold">
                                            Forgot Password
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <!-- Video feed for Camera-based attendance -->
                            <div class="video-container">
                                <video id="videoElement" autoplay></video>
                                <button class="btn-camera" onclick="captureAndSendData()">Capture Attendance</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('layouts.script')

<script>
// Accessing the camera and displaying the video stream
navigator.mediaDevices.getUserMedia({ video: true })
    .then(function (stream) {
        const video = document.getElementById('videoElement');
        video.srcObject = stream;
    })
    .catch(function (error) {
        console.log("Error accessing camera: ", error);
    });

// Function to capture the video frame and send it for processing
function captureAndSendData() {
    const video = document.getElementById('videoElement');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Draw the video frame on the canvas
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert the canvas image to a base64 string (can be sent to server)
    const imageData = canvas.toDataURL('image/png');

    // Send the captured image to the server for processing
    fetch('/api/attendance', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            image: imageData
        })
    })
    .then(response => response.json())
    .then(data => {
        alert("Attendance recorded: " + data.message);
    })
    .catch(error => {
        console.error('Error capturing attendance:', error);
    });
}
</script>
