const d = document,
	$attachBtn = d.getElementById('attach-btn')

function handleCreate(btn) {
	btn.preventDefault()
	d.querySelector('.modal-create').classList.remove('hidden')
}

function handleCancel(btn) {
	btn.preventDefault()
	d.querySelector('.modal-create').classList.add('hidden')
}

d.addEventListener('click', (e) => {
	if (e.target.matches('.create-btn')) {
		handleCreate(e)
	}
	if (e.target.matches('.modal-create .cancel-btn')) {
		handleCancel(e)
	}
})

$attachBtn.addEventListener('change', () => {
	const file = $attachBtn.files[0]
	if (file && file.size > 5 * 1024 * 1024) {
		alert('El archivo no puede superar los 5MB.')
		$attachBtn.value = ''
	}
})
