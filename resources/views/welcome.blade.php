<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>StudyHub | Integrated Learning Management</title>
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    </head>
    <body>
        <div class="hero-bg"></div>
        
        <div class="container">
            <nav>
                <div class="logo">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="url(#paint0_linear)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 17L12 22L22 17" stroke="url(#paint1_linear)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12L12 17L22 12" stroke="url(#paint2_linear)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <defs>
                            <linearGradient id="paint0_linear" x1="2" y1="2" x2="22" y2="12" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#6366f1"/>
                                <stop offset="1" stop-color="#a855f7"/>
                            </linearGradient>
                            <linearGradient id="paint1_linear" x1="2" y1="17" x2="22" y2="22" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#6366f1"/>
                                <stop offset="1" stop-color="#a855f7"/>
                            </linearGradient>
                            <linearGradient id="paint2_linear" x1="2" y1="12" x2="22" y2="17" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#6366f1"/>
                                <stop offset="1" stop-color="#a855f7"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    StudyHub
                </div>
                <div class="nav-links">
                    <a href="#">Courses</a>
                    <a href="#">Tutors</a>
                    <a href="#">About</a>
                </div>
                <div class="auth-btns">
                    <a href="/login" class="btn" style="color: white; margin-right: 1rem;">Login</a>
                    <a href="/login" class="btn btn-primary">Join StudyHub</a>
                </div>
            </nav>

            <main class="hero-section">
                <div class="hero-content">
                    <span class="badge badge-purple" style="margin-bottom: 1rem; display: inline-block;">All-in-One LMS</span>
                    <h1>Empowering <span class="h1-gradient">Academic</span> Excellence</h1>
                    <p class="lead">Integrated learning management for students, tutors, and administrators. Manage courses, track progress, and excel in your studies with StudyHub.</p>
                    
                    <div style="display: flex; gap: 1.5rem;">
                        <a href="/login" class="btn btn-primary" style="padding: 1rem 2.5rem;">Get Started Free</a>
                        <a href="/login" class="btn glass-card" style="padding: 1rem 2rem; color: #fff;">Explore Courses</a>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-item">
                            <h3 id="studentCount">0</h3>
                            <p>Active Students</p>
                        </div>
                        <div class="stat-item">
                            <h3 id="courseCount">0</h3>
                            <p>Premium Courses</p>
                        </div>
                        <div class="stat-item">
                            <h3 id="tutorCount">0</h3>
                            <p>Expert Tutors</p>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('images/hero-bg.png') }}" alt="StudyHub Hero" class="float-img">
                </div>
            </main>

            <section class="course-grid">
                <div class="glass-card course-card">
                    <span class="badge badge-purple">Computing</span>
                    <h2 style="margin: 1rem 0; font-size: 1.5rem;">Full-Stack Development</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Master the art of building modern web applications from scratch.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; font-size: 1.25rem;">$99.00</span>
                        <a href="/login" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Enroll Now</a>
                    </div>
                </div>

                <div class="glass-card course-card">
                    <span class="badge badge-purple">Business</span>
                    <h2 style="margin: 1rem 0; font-size: 1.5rem;">Digital Marketing Pro</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Grow your brand and reach your audience with data-driven strategies.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; font-size: 1.25rem;">$79.00</span>
                        <a href="/login" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Enroll Now</a>
                    </div>
                </div>

                <div class="glass-card course-card">
                    <span class="badge badge-purple">Design</span>
                    <h2 style="margin: 1rem 0; font-size: 1.5rem;">UI/UX Mastery</h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Craft beautiful digital experiences that users will love.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; font-size: 1.25rem;">$129.00</span>
                        <a href="/login" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">Enroll Now</a>
                    </div>
                </div>
            </section>

            <footer>
                <p>&copy; 2026 StudyHub Platform. All Rights Reserved.</p>
            </footer>
        </div>

        <script>
            // Simple animation for stats
            function animateValue(id, start, end, duration) {
                let obj = document.getElementById(id);
                let range = end - start;
                let minTimer = 50;
                let stepTime = Math.abs(Math.floor(duration / range));
                stepTime = Math.max(stepTime, minTimer);
                let startTime = new Date().getTime();
                let endTime = startTime + duration;
                let timer;

                function run() {
                    let now = new Date().getTime();
                    let remaining = Math.max((endTime - now) / duration, 0);
                    let value = Math.round(end - (remaining * range));
                    obj.innerHTML = value + (id === 'studentCount' ? 'k+' : '+');
                    if (value == end) {
                        clearInterval(timer);
                    }
                }

                timer = setInterval(run, stepTime);
                run();
            }

            window.onload = function() {
                animateValue("studentCount", 0, 25, 2000);
                animateValue("courseCount", 0, 150, 2000);
                animateValue("tutorCount", 0, 45, 2000);
            };
        </script>
    </body>
</html>
