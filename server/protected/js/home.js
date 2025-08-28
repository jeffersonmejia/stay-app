const d = document,
	$attachBtn = d.getElementById('attach-btn')

d.addEventListener('click', (e) => {
	if (e.target.matches('.create-btn')) {
		e.preventDefault()
		d.querySelector('.modal-create').classList.remove('hidden')
	}
	if (e.target.matches('.cancel-btn')) {
		d.querySelector('.modal-create').classList.add('hidden')
	}
})

$attachBtn.addEventListener('change', () => {
	const file = $attachBtn.files[0]
	if (file && file.size > 5 * 1024 * 1024) {
		alert('El archivo no puede superar los 5MB.')
		$attachBtn.value = ''
	}
})
