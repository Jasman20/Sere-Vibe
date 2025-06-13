// Home page - Navigate to signup page
const getStartedBtn = document.getElementById('get-started-btn');
if (getStartedBtn) {
  getStartedBtn.addEventListener('click', () => {
    window.location.href = 'signup.html';
  });
}