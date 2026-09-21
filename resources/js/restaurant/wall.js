import Konva from 'konva';
import { updateObject } from '../services/floorObjectService';

export default class Wall {

    constructor(layer, options = {}) {

        this.layer = layer;

        this.options = {

            id: options.id ?? null,

            x: options.x ?? 150,

            y: options.y ?? 150,

            width: options.width ?? 180,

            height: options.height ?? 18,

            rotation: options.rotation ?? 0

        };

        this.draw();

    }

    draw() {

        this.group = new Konva.Group({

    x: this.options.x,

    y: this.options.y,

    rotation: this.options.rotation,

    draggable: true,

    clipX: -this.options.width / 2,

    clipY: -this.options.height / 2,

    clipWidth: this.options.width,

    clipHeight: this.options.height

});

        this.wall = new Konva.Rect({

            x: -this.options.width / 2,

            y: -this.options.height / 2,

            width: this.options.width,

            height: this.options.height,

            fill: '#475569',

            stroke: '#334155',

            strokeWidth: 2,

            cornerRadius: 4

        });

        this.group.add(this.wall);

        this.layer.add(this.group);

        // Base
this.group.add(this.wall);

// Vetas de piedra
for (let i = 0; i < 35; i++) {

    const x = Math.random() * this.options.width - this.options.width / 2;
    const y = Math.random() * this.options.height - this.options.height / 2;

    const length = 8 + Math.random() * 15;

    const angle = Math.random() * Math.PI * 2;

    this.group.add(new Konva.Line({

        points: [
            x,
            y,
            x + Math.cos(angle) * length,
            y + Math.sin(angle) * length
        ],

        stroke: Math.random() > 0.5 ? '#8B9097' : '#D1D5DB',

        strokeWidth: 1,

        opacity: 0.45

    }));

    for (let i = 0; i < 15; i++) {

    this.group.add(new Konva.Circle({

        x: Math.random() * this.options.width - this.options.width / 2,

        y: Math.random() * this.options.height - this.options.height / 2,

        radius: 1 + Math.random() * 2,

        fill: '#6B7280',

        opacity: 0.18

    }));

}
}

    }

    select() {

        this.wall.stroke('#2563EB');

        this.wall.strokeWidth(4);

        this.layer.draw();

    }

    unselect() {

        this.wall.stroke('#334155');

        this.wall.strokeWidth(2);

        this.layer.draw();

    }

    async savePosition() {

        const position = this.group.position();

        this.options.x = position.x;
        this.options.y = position.y;

        try {

            await updateObject(this.options.id, {

                x: position.x,

                y: position.y

            });

        } catch (error) {

            console.error(error);

        }

    }

    async rotate(angle) {

    this.group.rotate(angle);

    this.options.rotation = this.group.rotation();

    this.layer.draw();

    try {

        await updateObject(this.options.id, {

            rotation: this.options.rotation

        });

    } catch (error) {

        console.error(error);

    }

}

}