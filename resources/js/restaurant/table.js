import Konva from 'konva';
import { updateObject } from '../services/floorObjectService';

export default class Table {

    constructor(layer, options = {}) {

        this.layer = layer;

        this.options = {
            id: options.id ?? null,
            name: options.name ?? 'Mesa',
            seats: options.seats ?? 4,
            x: options.x ?? 150,
            y: options.y ?? 150,
            shape: options.shape ?? 'square',
            status: options.status ?? 'available'
        };

        this.draw();
    }

    draw() {

        this.group = new Konva.Group({
            x: this.options.x,
            y: this.options.y,
            draggable: true
        });

        if (this.options.shape === 'round') {
            this.drawRoundTable();
        } else {
            this.drawSquareTable();
        }

        this.drawLabel();

        this.layer.add(this.group);
    }

    drawSquareTable() {

        this.table = new Konva.Rect({
            x: -40,
            y: -30,
            width: 80,
            height: 60,
            fill: '#FFFFFF',
            stroke: '#334155',
            strokeWidth: 2,
            cornerRadius: 8
        });

        this.group.add(this.table);

        const chairs = [
            [-55, -18],
            [55, -18],
            [-55, 18],
            [55, 18]
        ];

        chairs.forEach(([x, y]) => {

            this.group.add(new Konva.Circle({

                x,
                y,

                radius: 8,

                fill: '#94A3B8',

                stroke: '#475569',

                strokeWidth: 1

            }));

        });

    }

    drawRoundTable() {

        this.table = new Konva.Circle({

            radius: 35,

            fill: '#FFFFFF',

            stroke: '#334155',

            strokeWidth: 2

        });

        this.group.add(this.table);

        const radius = 55;

        for (let i = 0; i < 4; i++) {

            const angle = (Math.PI * 2 / 4) * i;

            this.group.add(new Konva.Circle({

                x: Math.cos(angle) * radius,

                y: Math.sin(angle) * radius,

                radius: 8,

                fill: '#94A3B8',

                stroke: '#475569',

                strokeWidth: 1

            }));

        }

    }

    drawLabel() {

        this.text = new Konva.Text({

            text: this.options.name,

            fontSize: 16,

            fontStyle: 'bold',

            fill: '#0F172A',

            width: 80,

            align: 'center',

            offsetX: 40,

            offsetY: 8

        });

        this.group.add(this.text);

    }

    select() {

        this.table.stroke('#2563EB');

        this.table.strokeWidth(4);

        this.layer.draw();

    }

    unselect() {

        this.table.stroke('#334155');

        this.table.strokeWidth(2);

        this.layer.draw();

    }

async setName(name) {

    this.options.name = name;

    this.text.text(name);

    this.layer.draw();

    try {

        await updateObject(this.options.id, {

            name: name

        });

    } catch (error) {

        console.error(error);

    }

}

async savePosition() {

    const position = this.group.position();

    console.log('Guardando posición:', position);

    this.options.x = position.x;
    this.options.y = position.y;

    try {

      await updateObject(this.options.id, {

    x: position.x,

    y: position.y

    

});


        console.log('Posición actualizada en la BD');

    } catch (error) {

        console.error(error);

    }

}

}