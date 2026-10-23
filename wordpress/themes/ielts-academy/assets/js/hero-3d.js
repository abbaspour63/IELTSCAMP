(function () {
    'use strict';

    const container = document.getElementById('ielts-hero-3d');

    if (!container || container.dataset.initialized === 'true') {
        return;
    }

    container.dataset.initialized = 'true';

    // پس‌زمینه گرادیان آبی و یاسی
    const backgroundStyle = document.createElement('style');

    backgroundStyle.textContent = `
        #ielts-hero-3d {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            background:
                radial-gradient(
                    ellipse at 20% 25%,
                    rgba(178, 189, 126, 0.42),
                    transparent 48%
                ),
                radial-gradient(
                    ellipse at 80% 75%,
                    rgba(217, 210, 139, 0.48),
                    transparent 48%
                ),
                linear-gradient(
                    135deg,
                    #F8F7EC 0%,
                    #F3F1D9 50%,
                    #EEF0E5 100%
                );
            background-size: 150% 150%;
            animation: ieltsHeroGradient 24s ease-in-out infinite alternate;
        }

        #ielts-hero-3d canvas {
            position: relative;
            z-index: 1;
        }

        @keyframes ieltsHeroGradient {
            0% {
                background-position: 0% 20%;
            }
            50% {
                background-position: 100% 80%;
            }
            100% {
                background-position: 20% 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            #ielts-hero-3d {
                animation: none;
            }
        }
    `;

    document.head.appendChild(backgroundStyle);


    import('https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js')
        .then(function (THREE) {
            if (!container.isConnected) return;

            const scene = new THREE.Scene();

            const camera = new THREE.PerspectiveCamera(
                40,
                container.clientWidth / Math.max(container.clientHeight, 1),
                0.1,
                100
            );
            camera.position.set(0, 0, 9);

            const renderer = new THREE.WebGLRenderer({
                alpha: true,
                antialias: true
            });

            renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
            renderer.setSize(container.clientWidth, container.clientHeight);
            renderer.outputColorSpace = THREE.SRGBColorSpace;
            container.appendChild(renderer.domElement);

            Object.assign(renderer.domElement.style, {
                width: '100%',
                height: '100%',
                display: 'block'
            });
            renderer.domElement.setAttribute('aria-hidden', 'true');

            scene.add(new THREE.AmbientLight(0xffffff, 2));

            const keyLight = new THREE.DirectionalLight(0xffffff, 2.5);
            keyLight.position.set(3, 5, 6);
            scene.add(keyLight);

            const blue = new THREE.MeshStandardMaterial({
                color: '#6A795D',
                roughness: 0.35,
                metalness: 0.08
            });

            const sky = new THREE.MeshStandardMaterial({
                color: '#B2BD7E',
                roughness: 0.3
            });

            const coral = new THREE.MeshStandardMaterial({
                color: '#D9D28B',
                roughness: 0.35
            });

            const cream = new THREE.MeshStandardMaterial({
                color: '#F8F7EC',
                roughness: 0.55
            });

            const white = new THREE.MeshStandardMaterial({
                color: '#FFFFFF',
                roughness: 0.4
            });

            const animated = [];

            function floatObject(object, speed, phase, amplitude) {
                scene.add(object);
                animated.push({
                    object: object,
                    baseY: object.position.y,
                    speed: speed || 0.7,
                    phase: phase || 0,
                    amplitude: amplitude || 0.12,
                    rotationSpeed: 0.0015 + Math.random() * 0.0015
                });
                return object;
            }

            function makeText(text, options) {
                const canvas = document.createElement('canvas');
                canvas.width = 512;
                canvas.height = 192;

                const context = canvas.getContext('2d');
                context.clearRect(0, 0, canvas.width, canvas.height);

                context.fillStyle = options.background || '#FFFFFF';
                context.beginPath();
                context.roundRect(8, 8, 496, 176, 32);
                context.fill();

                if (options.border) {
                    context.strokeStyle = options.border;
                    context.lineWidth = 5;
                    context.stroke();
                }

                context.fillStyle = options.color || '#244681';
                context.font = 'bold ' + (options.fontSize || 58) + 'px Arial, sans-serif';
                context.textAlign = 'center';
                context.textBaseline = 'middle';
                context.fillText(text, 256, 96, 460);

                const texture = new THREE.CanvasTexture(canvas);
                texture.colorSpace = THREE.SRGBColorSpace;

                const material = new THREE.MeshBasicMaterial({
                    map: texture,
                    transparent: true,
                    depthWrite: false,
                    side: THREE.DoubleSide
                });

                const mesh = new THREE.Mesh(
                    new THREE.PlaneGeometry(options.width || 1.8, options.height || 0.68),
                    material
                );

                mesh.position.set(
                    options.x || 0,
                    options.y || 0,
                    options.z || 0
                );

                return mesh;
            }

            // حلقه‌های تزئینی
            const ring = new THREE.Mesh(
                new THREE.TorusGeometry(1.45, 0.045, 12, 100),
                sky
            );
            ring.position.set(0, 0.15, -0.9);
            ring.rotation.set(0.55, 0.25, 0.1);
            floatObject(ring, 0.35, 0, 0.04);

            const innerRing = new THREE.Mesh(
                new THREE.TorusGeometry(1.05, 0.025, 10, 80),
                coral
            );
            innerRing.position.set(0, 0.15, -0.7);
            innerRing.rotation.set(0.8, -0.3, 0.3);
            floatObject(innerRing, 0.45, 1, 0.05);

            // کارت مرکزی IELTS
            const badge = makeText('IELTS 7.0', {
                background: '#6A795D',
                color: '#FFFFFF',
                width: 2.5,
                height: 0.9,
                fontSize: 65,
                x: 0,
                y: 0.15,
                z: 0.5
            });
            floatObject(badge, 0.65, 0.4, 0.12);

            // حباب‌های مکالمه
            const hello = makeText('Hello!', {
                background: '#FFFFFF',
                border: '#B2BD7E',
                color: '#6A795D',
                width: 1.65,
                height: 0.62,
                fontSize: 55,
                x: -1.8,
                y: 1.2,
                z: 0.4
            });
            floatObject(hello, 0.85, 1.2, 0.16);

            const practice = makeText("Let's practice!", {
                background: '#D9D28B',
                color: '#FFFFFF',
                width: 2.2,
                height: 0.65,
                fontSize: 42,
                x: 1.65,
                y: -1.15,
                z: 0.55
            });
            floatObject(practice, 0.7, 2, 0.14);

            // چهار مهارت IELTS
            const skills = [
                { text: 'LISTENING', x: -2.05, y: -0.45, color: '#D9D28B', ink: '#46533C' },
                { text: 'READING', x: 2.05, y: 0.75, color: '#F8F7EC', ink: '#6A795D' },
                { text: 'WRITING', x: -1.7, y: -1.55, color: '#D9D28B', ink: '#6A795D' },
                { text: 'SPEAKING', x: 1.95, y: 1.55, color: '#B2BD7E', ink: '#46533C' }
            ];

            skills.forEach(function (skill, index) {
                const card = makeText(skill.text, {
                    background: skill.color,
                    color: skill.ink,
                    width: 1.65,
                    height: 0.5,
                    fontSize: 36,
                    x: skill.x,
                    y: skill.y,
                    z: 0.1
                });
                floatObject(card, 0.55 + index * 0.08, index * 1.4, 0.09);
            });

            // کتاب سه‌بعدی
            // const book = new THREE.Group();

            // const cover = new THREE.Mesh(
            //     new THREE.BoxGeometry(0.95, 0.12, 1.2),
            //     coral
            // );
            // book.add(cover);

            // const pages = new THREE.Mesh(
            //     new THREE.BoxGeometry(0.88, 0.09, 1.12),
            //     cream
            // );
            // pages.position.y = 0.018;
            // book.add(pages);

            // const bookSpine = new THREE.Mesh(
            //     new THREE.BoxGeometry(0.12, 0.14, 1.22),
            //     blue
            // );
            // bookSpine.position.x = -0.42;
            // book.add(bookSpine);

            // book.position.set(-2.0, 0.05, 0.15);
            // book.rotation.set(0.3, 0.25, -0.32);
            // floatObject(book, 0.75, 1.7, 0.12);


            // کتاب باز سه‌بعدی برای یادگیری زبان انگلیسی
            const book = new THREE.Group();

            // جلد زیرین کتاب
            const bookCover = new THREE.Mesh(
                new THREE.BoxGeometry(1.35, 0.09, 0.95),
                blue
            );
            bookCover.position.y = -0.055;
            book.add(bookCover);

            // صفحات سمت چپ
            const leftPage = new THREE.Group();

            const leftPageMesh = new THREE.Mesh(
                new THREE.BoxGeometry(0.62, 0.035, 0.88),
                cream
            );
            leftPage.add(leftPageMesh);
            leftPage.position.set(-0.32, 0.015, 0);
            leftPage.rotation.z = -0.12;
            book.add(leftPage);

            // صفحات سمت راست
            const rightPage = new THREE.Group();

            const rightPageMesh = new THREE.Mesh(
                new THREE.BoxGeometry(0.62, 0.035, 0.88),
                white
            );
            rightPage.add(rightPageMesh);
            rightPage.position.set(0.32, 0.015, 0);
            rightPage.rotation.z = 0.12;
            book.add(rightPage);

            // خط‌های تزئینی روی صفحات
            const lineMaterial = new THREE.MeshBasicMaterial({
                color: '#8E9162'
            });

            for (let i = 0; i < 4; i++) {
                const lineLeft = new THREE.Mesh(
                    new THREE.BoxGeometry(0.34, 0.008, 0.018),
                    lineMaterial
                );
                lineLeft.position.set(-0.34, 0.04, -0.2 + i * 0.12);
                book.add(lineLeft);

                const lineRight = new THREE.Mesh(
                    new THREE.BoxGeometry(0.34, 0.008, 0.018),
                    lineMaterial
                );
                lineRight.position.set(0.34, 0.04, -0.2 + i * 0.12);
                book.add(lineRight);
            }

            book.position.set(-1.65, -0.65, 0.35);
            book.rotation.set(0.28, 0.18, -0.15);

            floatObject(book, 0.75, 1.7, 0.12);
          
            // حروف انگلیسی شناور در اطراف کتاب
            const floatingLetters = [
                {
                    text: 'A',
                    x: -2.05,
                    y: 0.65,
                    z: 0.7,
                    background: '#6A795D',
                    color: '#FFFFFF'
                },
                {
                    text: 'B',
                    x: -0.85,
                    y: 1.15,
                    z: 0.8,
                    background: '#D9D28B',
                    color: '#FFFFFF'
                },
                {
                    text: 'C',
                    x: -0.65,
                    y: -0.85,
                    z: 0.9,
                    background: '#D9D28B',
                    color: '#46533C'
                },
                {
                    text: 'Hi!',
                    x: -2.15,
                    y: -0.15,
                    z: 0.8,
                    background: '#F8F7EC',
                    color: '#6A795D'
                }
            ];

            floatingLetters.forEach(function (item, index) {
                const letter = makeText(item.text, {
                    background: item.background,
                    color: item.color,
                    width: 0.48,
                    height: 0.48,
                    fontSize: 100,
                    x: item.x,
                    y: item.y,
                    z: item.z
                });

                floatObject(
                    letter,
                    0.8 + index * 0.15,
                    index * 1.2,
                    0.16
                );
            });


            // حلقه کوچک و کره‌های شناور
            const shapes = [
                { geometry: new THREE.SphereGeometry(0.12, 20, 16), material: coral, x: -2.55, y: 0.65, z: 0.2 },
                { geometry: new THREE.SphereGeometry(0.16, 20, 16), material: sky, x: 2.45, y: -0.15, z: 0.2 },
                { geometry: new THREE.BoxGeometry(0.22, 0.22, 0.22), material: cream, x: 2.5, y: -1.55, z: 0.1 },
                { geometry: new THREE.SphereGeometry(0.09, 16, 12), material: blue, x: -0.9, y: 1.7, z: 0.2 }
            ];

            shapes.forEach(function (shape, index) {
                const mesh = new THREE.Mesh(shape.geometry, shape.material);
                mesh.position.set(shape.x, shape.y, shape.z);
                floatObject(mesh, 0.7 + index * 0.12, index, 0.16);
            });

            // موج صوتی برای Listening
            const waveGroup = new THREE.Group();

            for (let i = 0; i < 7; i++) {
                const height = 0.12 + Math.abs(Math.sin(i * 0.85)) * 0.42;
                const bar = new THREE.Mesh(
                    new THREE.BoxGeometry(0.055, height, 0.045),
                    i % 2 === 0 ? blue : sky
                );

                bar.position.set((i - 3) * 0.11, 0, 0);
                waveGroup.add(bar);
            }

            waveGroup.position.set(-2.05, -0.15, 0.5);
            floatObject(waveGroup, 0.4, 0.5, 0.04);

            const reducedMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            let pointerX = 0;
            let pointerY = 0;
            let frameId = 0;

            function onPointerMove(event) {
                const rect = container.getBoundingClientRect();
                if (!rect.width || !rect.height) return;

                pointerX = ((event.clientX - rect.left) / rect.width - 0.5) * 0.35;
                pointerY = ((event.clientY - rect.top) / rect.height - 0.5) * 0.25;
            }

            if (!reducedMotion) {
                container.addEventListener('pointermove', onPointerMove);
            }

            function resize() {
                const width = container.clientWidth;
                const height = container.clientHeight;

                if (!width || !height) return;

                camera.aspect = width / height;
                camera.position.z = width < 420 ? 10 : 9;
                camera.updateProjectionMatrix();
                renderer.setSize(width, height);
            }

            const resizeObserver = new ResizeObserver(resize);
            resizeObserver.observe(container);
            resize();

            const clock = new THREE.Clock();

            function animate() {
                frameId = requestAnimationFrame(animate);
                const time = clock.getElapsedTime();

                animated.forEach(function (item) {
                    if (!reducedMotion) {
                        item.object.position.y =
                            item.baseY +
                            Math.sin(time * item.speed + item.phase) * item.amplitude;

                        item.object.rotation.y += item.rotationSpeed;
                    }
                });

                // موج صوتی با ارتفاع متغیر
                waveGroup.children.forEach(function (bar, index) {
                    if (!reducedMotion) {
                        bar.scale.y = 0.6 + Math.abs(Math.sin(time * 3 + index)) * 1.1;
                    }
                });

                if (!reducedMotion) {
                    scene.rotation.y += (pointerX * 0.1 - scene.rotation.y) * 0.025;
                    scene.rotation.x += (-pointerY * 0.07 - scene.rotation.x) * 0.025;
                }

                renderer.render(scene, camera);
            }

            animate();

            window.addEventListener('pagehide', function () {
                cancelAnimationFrame(frameId);
                resizeObserver.disconnect();
                container.removeEventListener('pointermove', onPointerMove);

                scene.traverse(function (object) {
                    if (object.geometry) object.geometry.dispose();

                    if (object.material) {
                        const materials = Array.isArray(object.material)
                            ? object.material
                            : [object.material];

                        materials.forEach(function (material) {
                            if (material.map) material.map.dispose();
                            material.dispose();
                        });
                    }
                });

                renderer.dispose();
            }, { once: true });
        })
        .catch(function (error) {
            console.error('IELTSCAMP 3D scene could not load:', error);
        });
})();
