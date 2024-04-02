<script lang="ts">
  interface IncidentForm {
    name: string
    surname: string
    email: string
    description: string
    images?: FileList
  }

  let form: IncidentForm = {
    name: '',
    surname: '',
    email: '',
    description: '',
  }

  const prefix = import.meta.env.PUBLIC_API_URL || ''

  function reportIncident() {
    // TODO: validation

    const data = new FormData()

    data.append('name', form.name)
    data.append('surname', form.surname)
    data.append('email', form.email)
    data.append('description', form.description)

    if (form.images) {
      for (let i = 0; i < form.images.length; i++) {
        data.append('images[]', form.images[i])
      }
    }

    fetch(prefix + '/api/incidents/', {
      method: 'POST',
      body: data,
      headers: {
        Accept: 'application/json',
      },
    })
      .then((res) => res.json())
      .then((res) => {
        console.log(res)
        alert('Incident reported successfully!')
      })
      .catch((err) => {
        console.error(err)
        alert('Error reporting incident!')
      })
  }
</script>

<form on:submit|preventDefault={reportIncident}>
  <div>
    <div class="form-control w-full max-w-xs">
      <label class="label" for="name">
        <span class="label-text">Name</span>
      </label>
      <input
        id="name"
        type="text"
        placeholder="Type here"
        class="input input-bordered w-full max-w-xs"
        bind:value={form.name}
      />
    </div>

    <div class="form-control w-full max-w-xs">
      <label class="label" for="surname">
        <span class="label-text">Surname</span>
      </label>
      <input
        id="surname"
        type="text"
        placeholder="Type here"
        class="input input-bordered w-full max-w-xs"
        bind:value={form.surname}
      />
    </div>

    <div class="form-control w-full max-w-xs">
      <label class="label" for="email">
        <span class="label-text">E-Mail</span>
      </label>
      <input
        id="email"
        type="text"
        placeholder="Type here"
        class="input input-bordered w-full max-w-xs"
        bind:value={form.email}
      />
    </div>

    <div class="form-control w-full max-w-xs">
      <label class="label" for="description">
        <span class="label-text">Description</span>
      </label>
      <textarea
        id="description"
        class="textarea textarea-bordered h-24"
        placeholder="Description"
        bind:value={form.description}
      ></textarea>
    </div>

    <div class="form-control w-full max-w-xs">
      <label class="label" for="images">
        <span class="label-text">Images</span>
      </label>
      <input
        id="images"
        type="file"
        class="file-input file-input-bordered w-full max-w-xs"
        multiple
        accept="image/*"
        bind:files={form.images}
      />
    </div>
  </div>

  <button type="submit" class="btn btn-primary mt-2">Submit</button>
</form>
