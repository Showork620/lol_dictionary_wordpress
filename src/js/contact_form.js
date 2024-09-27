// ============================================================
//  src/js/contact_form.js
// ============================================================

const textAreas = document.querySelectorAll('input[type="text"], textarea', 'label');

textAreas.forEach((textArea) => {

	textArea.addEventListener('click', () => {
		textArea.focus();
	});
});