export async function saveObject(data) {

    const response = await fetch('/api/restaurant-objects', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',

            'Accept': 'application/json',

            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },

        body: JSON.stringify(data)

    });

  if (!response.ok) {

    const error = await response.json();

    console.error(error);

    throw new Error(error.message ?? 'No se pudo guardar el objeto.');

}

    return await response.json();

}
export async function loadObjects(areaId) {

    const response = await fetch(`/api/restaurant-objects?area_id=${areaId}`, {

        headers: {

            'Accept': 'application/json'

        }

    });

    if (!response.ok) {

        throw new Error('No fue posible cargar el plano.');

    }

    return await response.json();

}

export async function updateObject(id, data) {

    const response = await fetch(`/api/restaurant-objects/${id}`, {

        method: 'PUT',

        headers: {

            'Content-Type': 'application/json',

            'Accept': 'application/json',

            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content

        },

        body: JSON.stringify(data)

    });
if (!response.ok) {

    const error = await response.json();

    console.error(error);

    throw new Error(error.message);

}

    return await response.json();

}

export async function deleteObject(id) {

    const response = await fetch(`/api/restaurant-objects/${id}`, {

        method: 'DELETE',

        headers: {

            'Accept': 'application/json',

            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content

        }

    });

    if (!response.ok) {

        throw new Error('No fue posible eliminar el objeto.');

    }

    return await response.json();

}