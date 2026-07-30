export async function loadAreas() {

    const response = await fetch('/api/restaurant-areas', {

        headers: {
            Accept: 'application/json'
        }

    });

    if (!response.ok) {
        throw new Error('No fue posible cargar las áreas.');
    }

    return await response.json();

}


export async function saveArea(data) {

    const response = await fetch('/api/restaurant-areas', {

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

    const result = await response.json();

    if (!response.ok) {

        console.error(result);

        throw new Error(result.message ?? 'No fue posible crear el área.');

    }

    return result;

}