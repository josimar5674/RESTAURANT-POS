import Konva from 'konva';
import Table from './table';
import { loadAreas,saveArea } from '../services/restaurantAreaService';
import Wall from './wall';

import {
    saveObject,
    loadObjects,
    updateObject,
    deleteObject
} from '../services/floorObjectService';


const container = document.getElementById('konva-container');

let selectedObject = null;

let editorMode = 'select';

if (container) {

    const stage = new Konva.Stage({

        container: 'konva-container',

        width: container.offsetWidth,

        height: container.offsetHeight,

    });

    const layer = new Konva.Layer();

    stage.add(layer);

    // Fondo
    layer.add(new Konva.Rect({

        x: 0,
        y: 0,

        width: stage.width(),
        height: stage.height(),

        fill: '#F8FAFC'

    }));

    // ===========================
    // Mesas
    // ===========================


    function registerTable(table) {

        tables.push(table);

        table.group.on('click', (e) => {

            e.cancelBubble = true;

            if (selectedObject) {
                selectedObject.unselect();
            }

            selectedObject = table;
            const input = document.getElementById('table-name');

            input.disabled = false;
            deleteButton.disabled = false;
            rotateLeft.disabled = false;

            rotateRight.disabled = false;

            input.value = table.options.name;

            selectedObject.select();

        });


        table.group.on('dragend', () => {

            console.log(table.group.position());

            table.savePosition();

            

        });



    }


function registerWall(wall) {

    tables.push(wall);

    wall.group.on('click', (e) => {

        e.cancelBubble = true;

        if (selectedObject) {
            selectedObject.unselect();
        }

        selectedObject = wall;

        tableName.disabled = true;
        tableName.value = '';

        rotateLeft.disabled = false;

        rotateRight.disabled = false;

        deleteButton.disabled = false;

        wall.select();

    });

    wall.group.on('dragend', () => {

        wall.savePosition();

    });

}


    const tables = [];
    const toolSelect = document.getElementById('tool-select');
    const toolSquare = document.getElementById('tool-table-square');
    const toolRound = document.getElementById('tool-table-round');
    const toolWall = document.getElementById('tool-wall');

    function activateTool(mode) {

        editorMode = mode;

        [toolSelect, toolSquare, toolRound].forEach(button => {
            button.classList.remove('bg-blue-600', 'text-white');
        });

        switch (mode) {
            case 'select':
                toolSelect.classList.add('bg-blue-600', 'text-white');
                break;
            case 'table-square':
                toolSquare.classList.add('bg-blue-600', 'text-white');
                break;
            case 'table-round':
                toolRound.classList.add('bg-blue-600', 'text-white');
                break;
            case 'wall':

                toolWall.classList.add('bg-blue-600', 'text-white');

                break;
        }
    }

    activateTool('select');

    toolSelect.addEventListener('click', () => activateTool('select'));
    toolSquare.addEventListener('click', () => activateTool('table-square'));
    toolRound.addEventListener('click', () => activateTool('table-round'));
    toolWall.addEventListener('click', () => activateTool('wall'));

    stage.on('click', async (e) => {

        // Si estamos agregando una mesa...
        if (editorMode === 'table-square' || editorMode === 'table-round') {

            const position = stage.getPointerPosition();

            const objectData = {

                type: 'table',

                name: `Mesa ${tables.length + 1}`,

                area_id: currentArea,

                x: position.x,

                y: position.y,

                rotation: 0,

                width: 80,

                height: 60,

                shape: editorMode === 'table-round' ? 'round' : 'square',

                style: 'default',

                properties: {}

            };
try {

    const response = await saveObject(objectData);

    console.log('RESPUESTA:', response);

    const table = new Table(layer, {

        id: response.data.id,

        name: response.data.name,

        x: response.data.x,

        y: response.data.y,

        rotation: Number(response.data.rotation),

        shape: response.data.shape

    });

    console.log('MESA CREADA');

    registerTable(table);

    console.log('MESA REGISTRADA');

    layer.draw();

    activateTool('select');

} catch (error) {

    console.error(error);

    console.error(error.stack);

    alert(error.message);

}

            return;
        }

        if (editorMode === 'wall') {

    const position = stage.getPointerPosition();

    const objectData = {

        area_id: currentArea,

        type: 'wall',

        name: null,

        x: position.x,

        y: position.y,

        rotation: 0,

        width: 180,

        height: 18,

        shape: null,

        style: 'stone',

        properties: {}

    };

    try {

        const response = await saveObject(objectData);

        const wall = new Wall(layer, {

            id: response.data.id,

            x: response.data.x,

            y: response.data.y,

            width: response.data.width,

            height: response.data.height,

            rotation: response.data.rotation

        });

                registerWall(wall);

                layer.draw();

                activateTool('select');
        layer.draw();

        activateTool('select');

    } catch (error) {

        console.error(error);

        alert('No fue posible guardar el muro.');

    }

    return;

}

        // Deseleccionar
        if (selectedObject) {

            selectedObject.unselect();

            selectedObject = null;
            tableName.value = '';
            tableName.disabled = true;

            deleteButton.disabled = true;
            rotateLeft.disabled = true; 
            rotateRight.disabled = true;
            

        }

    });

    async function loadFloor(areaId) {

        try {

            const objects = await loadObjects(areaId);

            objects.forEach(object => {

                switch (object.type) {

                    case 'table':

                        registerTable(

                            new Table(layer, {

                                id: object.id,

                                name: object.name,
                                    rotation: Number(object.rotation),
                                x: Number(object.x),
                                y: Number(object.y),
                                shape: object.shape

                            })

                        );

                        break;

                        case 'wall':

    registerWall(

        new Wall(layer, {

            id: object.id,

            x: Number(object.x),

            y: Number(object.y),

            width: Number(object.width),

            height: Number(object.height),

            rotation: Number(object.rotation)

        })

    );

    break;

                }

            });

            layer.draw();

        } catch (error) {

            console.error(error);

        }

    }

    const tableName = document.getElementById('table-name');
    const deleteButton = document.getElementById('btn-delete-object');
    const areaSelector = document.getElementById('area-selector');
    const newAreaButton = document.getElementById('btn-new-area');
const rotateLeft = document.getElementById('btn-rotate-left');
const rotateRight = document.getElementById('btn-rotate-right');
    let currentArea = null;

    if (tableName) {

        tableName.addEventListener('change', () => {

            if (!selectedObject) return;

            selectedObject.setName(tableName.value);

        });

    }


    deleteButton.addEventListener('click', async () => {

        console.log('CLICK EN EL BOTÓN');

        if (!selectedObject) return;

        if (!confirm(`¿Desea eliminar "${selectedObject.options.name}"?`)) {
            return;
        }

        try {

            await deleteObject(selectedObject.options.id);

            selectedObject.group.destroy();

            const index = tables.indexOf(selectedObject);

            if (index > -1) {
                tables.splice(index, 1);
            }

            selectedObject = null;

            tableName.value = '';
            tableName.disabled = true;

            deleteButton.disabled = true;
            rotateLeft.disabled = true;
            rotateRight.disabled = true;

            layer.draw();

        } catch (error) {

            console.error(error);

            alert('No fue posible eliminar la mesa.');

        }

    });

await loadAreaSelector();

await loadFloor(currentArea);

    areaSelector.addEventListener('change', async () => {

        currentArea = Number(areaSelector.value);

        // Limpiar objetos actuales
        tables.forEach(table => table.group.destroy());

        tables.length = 0;

        selectedObject = null;

        layer.draw();

        await loadFloor(currentArea);

    });
    async function loadAreaSelector() {

        try {

            const areas = await loadAreas();

            areaSelector.innerHTML = '';

            areas.forEach(area => {

                const option = document.createElement('option');

                option.value = area.id;

                option.textContent = area.name;

                areaSelector.appendChild(option);

            });

            if (areas.length) {

                currentArea = areas[0].id;

                areaSelector.value = currentArea;

            }

        } catch (error) {

            console.error(error);

        }

    }
    
newAreaButton.addEventListener('click', async () => {

    const name = prompt('Nombre del área');

    if (!name) return;

    try {

   const area = await saveArea({ name });

await loadAreaSelector();

areaSelector.value = area.data.id;

currentArea = area.data.id;

tables.forEach(table => table.group.destroy());

tables.length = 0;

await loadFloor(currentArea);

    } catch (error) {

        console.error(error);

        alert('No fue posible crear el área.');

    }

});
    


rotateLeft.addEventListener('click', () => {

    if (!selectedObject) return;

    selectedObject.rotate(-15);

});

rotateRight.addEventListener('click', () => {

        console.log('ROTAR +');

    if (!selectedObject) return;

    selectedObject.rotate(15);

});
}

