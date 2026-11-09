import * as THREE from '../libs/three/three.module.min.js';

const canvas = document.getElementById('scene');
const loader = document.getElementById('loader');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let renderer, scene, camera, world, dino, dinoHead, dinoArmR, tailGroup, bubblesA, bubblesB, shapes, clock;
let running = true;
const pointer = { x: 0, y: 0 };
const target = { x: 0, y: 0 };
const bubbleDataA = [];
const bubbleDataB = [];
const eyeL = [];
const eyeR = [];
let blinkTime = 0;
let nextBlink = 3.2;
const dummy = new THREE.Object3D();

function finishLoading() {
  if (!loader) return;
  loader.classList.add('hide');
  setTimeout(() => loader.remove(), 700);
}

function makeBlobShadow() {
  const c = document.createElement('canvas');
  c.width = c.height = 256;
  const ctx = c.getContext('2d');
  const g = ctx.createRadialGradient(128, 128, 10, 128, 128, 120);
  g.addColorStop(0, 'rgba(15,59,67,.30)');
  g.addColorStop(.55, 'rgba(15,59,67,.10)');
  g.addColorStop(1, 'rgba(15,59,67,0)');
  ctx.fillStyle = g;
  ctx.fillRect(0, 0, 256, 256);
  const tex = new THREE.CanvasTexture(c);
  tex.colorSpace = THREE.SRGBColorSpace;
  return tex;
}

function addSpot(parent, x, y, z, r) {
  const s = new THREE.Mesh(
    new THREE.SphereGeometry(r, 16, 12),
    new THREE.MeshStandardMaterial({ color: 0xf2a9a0, roughness: .62 })
  );
  s.position.set(x, y, z);
  s.scale.set(1, 1, .22);
  s.lookAt(new THREE.Vector3(0, 0, 0));
  parent.add(s);
  return s;
}

function buildDino() {
  const g = new THREE.Group();

  const bodyMat = new THREE.MeshPhysicalMaterial({ color: 0xd8e7a8, roughness: .5, clearcoat: .35, clearcoatRoughness: .55 });
  const bodyShade = new THREE.MeshStandardMaterial({ color: 0xb8d187, roughness: .6 });
  const bellyMat = new THREE.MeshStandardMaterial({ color: 0xeef8dc, roughness: .75 });
  const waterMat = new THREE.MeshStandardMaterial({ color: 0xbfe3e0, roughness: .45, metalness: .04 });
  const spotMat = new THREE.MeshStandardMaterial({ color: 0xf2a9a0, roughness: .62 });
  const blushMat = new THREE.MeshStandardMaterial({ color: 0xf7b3ab, roughness: .8 });
  const eyeMat = new THREE.MeshStandardMaterial({ color: 0x22332e, roughness: .18 });
  const whiteMat = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: .15 });
  const cloudMat = new THREE.MeshStandardMaterial({ color: 0xffffff, roughness: .95 });
  const cloudShade = new THREE.MeshStandardMaterial({ color: 0xeaf6ee, roughness: .95 });

  const shadow = new THREE.Mesh(
    new THREE.PlaneGeometry(5.2, 5.2),
    new THREE.MeshBasicMaterial({ map: makeBlobShadow(), transparent: true, depthWrite: false, opacity: .75 })
  );
  shadow.rotation.x = -Math.PI / 2;
  shadow.position.set(0, -2.15, 0);
  g.add(shadow);

  const cloud = new THREE.Group();
  cloud.position.set(0, -1.72, 0);
  g.add(cloud);
  const puff = (x, y, z, r, sx, sy, sz, mat) => {
    const m = new THREE.Mesh(new THREE.SphereGeometry(r, 26, 18), mat);
    m.position.set(x, y, z);
    m.scale.set(sx, sy, sz);
    cloud.add(m);
  };
  puff(0, 0, 0, .95, 1.55, .5, 1, cloudMat);
  puff(-1.1, -.08, 0, .58, 1, .6, 1, cloudMat);
  puff(1.1, -.08, 0, .58, 1, .6, 1, cloudMat);
  puff(-.55, -.16, .28, .52, 1, .62, 1, cloudShade);
  puff(.55, -.16, .28, .52, 1, .62, 1, cloudShade);
  puff(0, .2, -.35, .68, 1.3, .55, 1, cloudMat);

  const body = new THREE.Mesh(new THREE.SphereGeometry(1.3, 36, 28), bodyMat);
  body.scale.set(1.0, 1.02, .96);
  g.add(body);

  const belly = new THREE.Mesh(new THREE.SphereGeometry(1.0, 30, 22), bellyMat);
  belly.scale.set(.8, .8, .34);
  belly.position.set(0, -.12, .98);
  g.add(belly);

  const water = new THREE.Mesh(new THREE.SphereGeometry(.62, 26, 18), waterMat);
  water.scale.set(.7, .76, .16);
  water.position.set(0, -.24, 1.2);
  g.add(water);

  const glint = new THREE.Mesh(new THREE.SphereGeometry(.1, 12, 8), whiteMat);
  glint.scale.set(1.4, .5, .3);
  glint.position.set(.2, .02, 1.32);
  glint.rotation.z = -.4;
  g.add(glint);

  const legGeo = new THREE.CapsuleGeometry(.27, .28, 6, 14);
  [[-.62, -1.08, .4], [.62, -1.08, .4], [-.55, -1.1, -.28], [.55, -1.1, -.28]].forEach(([x, y, z]) => {
    const leg = new THREE.Mesh(legGeo, bodyShade);
    leg.position.set(x, y, z);
    leg.rotation.z = x < 0 ? -.1 : .1;
    g.add(leg);
    const foot = new THREE.Mesh(new THREE.SphereGeometry(.3, 18, 14), bodyShade);
    foot.scale.set(1.1, .68, 1.25);
    foot.position.set(x, y - .4, z + .08);
    g.add(foot);
    addSpot(g, x + .02, y - .18, z + .27, .1);
  });

  const armGeo = new THREE.CapsuleGeometry(.17, .2, 6, 12);
  const armL = new THREE.Group();
  armL.position.set(-1.06, .02, .34);
  g.add(armL);
  const la = new THREE.Mesh(armGeo, bodyMat);
  la.position.set(-.18, -.16, .06);
  la.rotation.z = .65;
  armL.add(la);
  const armLFoot = new THREE.Mesh(new THREE.SphereGeometry(.2, 16, 12), bodyShade);
  armLFoot.scale.set(1, .85, 1);
  armLFoot.position.set(-.34, -.36, .1);
  armL.add(armLFoot);

  dinoArmR = new THREE.Group();
  dinoArmR.position.set(1.02, .12, .34);
  g.add(dinoArmR);
  const ra = new THREE.Mesh(armGeo, bodyMat);
  ra.position.set(.2, .2, .06);
  ra.rotation.z = -.85;
  dinoArmR.add(ra);
  const armRFoot = new THREE.Mesh(new THREE.SphereGeometry(.2, 16, 12), bodyShade);
  armRFoot.scale.set(1, .85, 1);
  armRFoot.position.set(.38, .38, .1);
  dinoArmR.add(armRFoot);

  tailGroup = new THREE.Group();
  tailGroup.position.set(-.95, -.05, -.6);
  g.add(tailGroup);
  const curve = new THREE.CatmullRomCurve3([
    new THREE.Vector3(0, 0, 0),
    new THREE.Vector3(-.5, -.02, -.08),
    new THREE.Vector3(-.95, -.2, -.2),
    new THREE.Vector3(-1.24, -.52, -.22),
    new THREE.Vector3(-1.16, -.8, -.12)
  ]);
  const tail = new THREE.Mesh(new THREE.TubeGeometry(curve, 42, .15, 10, false), bodyMat);
  tailGroup.add(tail);
  const tailTip = new THREE.Mesh(new THREE.ConeGeometry(.13, .3, 10), bodyMat);
  tailTip.position.set(-1.16, -.93, -.1);
  tailTip.rotation.z = -.2;
  tailGroup.add(tailTip);
  addSpot(tailGroup, -.95, -.35, -.2, .1);

  dinoHead = new THREE.Group();
  dinoHead.position.set(.55, 1.34, .08);
  g.add(dinoHead);
  const head = new THREE.Mesh(new THREE.SphereGeometry(1.0, 36, 28), bodyMat);
  head.scale.set(1.06, 1.0, .95);
  dinoHead.add(head);

  const snout = new THREE.Mesh(new THREE.SphereGeometry(.6, 30, 22), bellyMat);
  snout.scale.set(1.24, .72, .92);
  snout.position.set(.42, -.18, .66);
  dinoHead.add(snout);

  [[.5, 0, 1.22], [.72, 0, 1.16]].forEach(([x, y, z]) => {
    const n = new THREE.Mesh(new THREE.SphereGeometry(.038, 10, 8), eyeMat);
    n.position.set(x, y, z);
    dinoHead.add(n);
  });

  const smile = new THREE.Mesh(new THREE.TorusGeometry(.17, .03, 8, 24, Math.PI), eyeMat);
  smile.position.set(.55, -.3, 1.18);
  smile.rotation.z = Math.PI;
  dinoHead.add(smile);

  [[.2, .2, .84], [-.32, .22, .8]].forEach(([x, y, z], i) => {
    const white = new THREE.Mesh(new THREE.SphereGeometry(.22, 22, 16), whiteMat);
    white.scale.set(1, 1, .62);
    white.position.set(x, y, z);
    dinoHead.add(white);
    const pupil = new THREE.Mesh(new THREE.SphereGeometry(.115, 18, 12), eyeMat);
    pupil.scale.set(1, 1, .7);
    pupil.position.set(x + .02, y + .01, z + .12);
    dinoHead.add(pupil);
    const shine = new THREE.Mesh(new THREE.SphereGeometry(.045, 10, 8), whiteMat);
    shine.position.set(x + .07, y + .07, z + .19);
    dinoHead.add(shine);
    (i === 0 ? eyeL : eyeR).push(white, pupil);
  });

  [[.62, -.14, .84], [-.5, -.06, .78]].forEach(([x, y, z]) => {
    const c = new THREE.Mesh(new THREE.SphereGeometry(.14, 16, 12), blushMat);
    c.scale.set(1, .62, .3);
    c.position.set(x, y, z);
    dinoHead.add(c);
  });

  const earSpot = new THREE.Mesh(new THREE.SphereGeometry(.3, 20, 14), spotMat);
  earSpot.scale.set(1, .9, .35);
  earSpot.position.set(-.62, .52, .42);
  earSpot.lookAt(new THREE.Vector3(0, 0, 0));
  dinoHead.add(earSpot);

  [[-.05, .92, -.12, .24], [-.42, .78, -.34, .18]].forEach(([x, y, z, r]) => {
    const plate = new THREE.Mesh(new THREE.SphereGeometry(r, 16, 12), bodyShade);
    plate.scale.set(1, .7, .4);
    plate.position.set(x, y, z);
    dinoHead.add(plate);
    const dot = new THREE.Mesh(new THREE.SphereGeometry(r * .34, 12, 8), spotMat);
    dot.position.set(x, y + r * .3, z - .02);
    dinoHead.add(dot);
  });

  addSpot(g, -.62, .55, -.92, .17);
  addSpot(g, .58, .32, -1.0, .14);
  addSpot(g, .92, -.42, -.66, .12);
  addSpot(g, -.92, -.18, -.6, .13);
  addSpot(g, .18, .86, -.72, .15);
  addSpot(g, .32, .62, .86, .1);
  addSpot(g, .96, .5, .36, .12);
  addSpot(g, -.88, .58, .44, .1);
  addSpot(g, .84, -.48, .6, .09);
  addSpot(g, -.8, -.38, .58, .08);

  return g;
}

function makeTorus(color, pos, scale, rot) {
  const m = new THREE.Mesh(
    new THREE.TorusGeometry(1, .26, 20, 60),
    new THREE.MeshStandardMaterial({ color, roughness: .45, metalness: .05, transparent: true, opacity: .9 })
  );
  m.position.set(...pos);
  m.scale.setScalar(scale);
  m.rotation.set(...rot);
  return m;
}

function makeSphere(color, pos, r, opacity = .5) {
  const m = new THREE.Mesh(
    new THREE.SphereGeometry(r, 28, 20),
    new THREE.MeshStandardMaterial({ color, roughness: .35, metalness: .05, transparent: true, opacity })
  );
  m.position.set(...pos);
  return m;
}

function onResize() {
  const w = window.innerWidth;
  const h = window.innerHeight;
  renderer.setSize(w, h);
  camera.aspect = w / h;
  const mobile = w < 720;
  camera.position.z = mobile ? 16.2 : 13.7;
  camera.position.y = mobile ? 0.45 : 0.15;
  dino.position.set(mobile ? 2.35 : 4.35, mobile ? -4.55 : -0.72, mobile ? -2.6 : 0);
  dino.scale.setScalar(mobile ? .5 : 1.04);
  dino.rotation.y = mobile ? -0.2 : -0.38;
  camera.updateProjectionMatrix();
}

function onPointerMove(e) {
  pointer.x = (e.clientX / window.innerWidth) * 2 - 1;
  pointer.y = (e.clientY / window.innerHeight) * 2 - 1;
}

function animate() {
  if (!running) return;
  const t = clock.getElapsedTime();

  target.x += (pointer.x * .22 - target.x) * .04;
  target.y += (pointer.y * .12 - target.y) * .04;
  world.rotation.y = target.x;
  world.rotation.x = target.y * .5;

  const mobile = window.innerWidth < 720;
  const baseY = mobile ? -4.55 : -0.72;
  dino.position.y = baseY + Math.sin(t * 1.15) * 0.17;
  dino.rotation.z = Math.sin(t * .9) * 0.022;
  if (dinoHead) {
    dinoHead.rotation.z = Math.sin(t * 1.2 + 1) * 0.05;
    dinoHead.rotation.y += ((target.x * 0.18) - dinoHead.rotation.y) * .04;
    dinoHead.rotation.x += ((-target.y * 0.1) - dinoHead.rotation.x) * .04;
  }
  if (dinoArmR) dinoArmR.rotation.z = -0.12 + Math.sin(t * 2.1) * 0.22;
  if (tailGroup) tailGroup.rotation.y = Math.sin(t * 1.6) * 0.3;
  if (shapes) shapes.rotation.y = Math.sin(t * .18) * .08;

  blinkTime += 1 / 60;
  if (blinkTime > nextBlink) {
    const k = Math.max(0.05, Math.abs(Math.cos((blinkTime - nextBlink) * 7)));
    [...eyeL, ...eyeR].forEach(e => e.scale.y = k);
    if (blinkTime > nextBlink + .22) {
      [...eyeL, ...eyeR].forEach(e => e.scale.y = 1);
      blinkTime = 0;
      nextBlink = 3.4 + Math.random() * 3;
    }
  }

  const updateBubbles = (mesh, data) => {
    data.forEach((d, i) => {
      d.y += d.s * 0.012;
      if (d.y > 5.2) {
        d.y = -5.2;
        d.x = (Math.random() - .5) * 11;
      }
      const wob = Math.sin(t * .7 + d.p) * .18;
      dummy.position.set(d.x + wob, d.y, d.z);
      dummy.scale.setScalar(d.r);
      dummy.updateMatrix();
      mesh.setMatrixAt(i, dummy.matrix);
    });
    mesh.instanceMatrix.needsUpdate = true;
  };
  updateBubbles(bubblesA, bubbleDataA);
  updateBubbles(bubblesB, bubbleDataB);

  renderer.render(scene, camera);
  requestAnimationFrame(animate);
}

function init() {
  renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true, powerPreference: 'high-performance' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.08;

  scene = new THREE.Scene();
  camera = new THREE.PerspectiveCamera(42, 1, .1, 100);
  camera.position.set(0, 0.15, 13.7);

  world = new THREE.Group();
  scene.add(world);

  scene.add(new THREE.HemisphereLight(0xffffff, 0xdcf7ec, 1.25));
  const key = new THREE.DirectionalLight(0xffffff, 2.4);
  key.position.set(5, 8, 7);
  scene.add(key);
  const fill = new THREE.PointLight(0xc4b5fd, 34, 30, 2);
  fill.position.set(-6, 2.5, 5);
  scene.add(fill);
  const rim = new THREE.PointLight(0x8fd6b6, 16, 30, 2);
  rim.position.set(6, -2, 4);
  scene.add(rim);

  dino = buildDino();
  dino.position.set(4.35, -0.72, 0);
  dino.rotation.y = -0.38;
  dino.scale.setScalar(1.04);
  world.add(dino);

  shapes = new THREE.Group();
  world.add(shapes);
  shapes.add(makeTorus(0x6fc2a3, [-4.6, 2.2, -1.5], .62, [.4, .7, .2]));
  shapes.add(makeTorus(0xc4b5fd, [4.9, 2.8, -2.2], .5, [.8, .2, .5]));
  shapes.add(makeTorus(0xffb5a7, [-3.9, -2.7, -1.2], .38, [.2, .9, .6]));
  shapes.add(makeSphere(0xb9e2d1, [-5.2, -.6, -2], .42, .55));
  shapes.add(makeSphere(0xc4b5fd, [3.2, 3.4, -3], .34, .5));

  const bubbleGeo = new THREE.SphereGeometry(1, 16, 12);
  const bubMatA = new THREE.MeshStandardMaterial({ color: 0xa7e3c8, transparent: true, opacity: .28, roughness: .15, metalness: 0 });
  const bubMatB = new THREE.MeshStandardMaterial({ color: 0xc4b5fd, transparent: true, opacity: .24, roughness: .15, metalness: 0 });
  bubblesA = new THREE.InstancedMesh(bubbleGeo, bubMatA, 14);
  bubblesB = new THREE.InstancedMesh(bubbleGeo, bubMatB, 10);
  for (let i = 0; i < 14; i++) {
    const d = { x: (Math.random() - .5) * 11, y: (Math.random() - .5) * 8, z: -4 + Math.random() * 3, r: .07 + Math.random() * .19, s: .25 + Math.random() * .5, p: Math.random() * Math.PI * 2 };
    bubbleDataA.push(d);
    dummy.position.set(d.x, d.y, d.z);
    dummy.scale.setScalar(d.r);
    dummy.updateMatrix();
    bubblesA.setMatrixAt(i, dummy.matrix);
  }
  for (let i = 0; i < 10; i++) {
    const d = { x: (Math.random() - .5) * 11, y: (Math.random() - .5) * 8, z: -5 + Math.random() * 3, r: .06 + Math.random() * .16, s: .18 + Math.random() * .4, p: Math.random() * Math.PI * 2 };
    bubbleDataB.push(d);
    dummy.position.set(d.x, d.y, d.z);
    dummy.scale.setScalar(d.r);
    dummy.updateMatrix();
    bubblesB.setMatrixAt(i, dummy.matrix);
  }
  world.add(bubblesA, bubblesB);

  clock = new THREE.Clock();
  window.addEventListener('resize', onResize);
  window.addEventListener('pointermove', onPointerMove);
  document.addEventListener('visibilitychange', () => {
    running = !document.hidden;
    if (running && !reduceMotion) {
      clock.getDelta();
      requestAnimationFrame(animate);
    }
  });
  onResize();

  renderer.render(scene, camera);
  canvas.classList.add('ready');
  finishLoading();
  window.__dinoSceneReady = true;

  if (!reduceMotion) requestAnimationFrame(animate);
}

try {
  init();
} catch (err) {
  console.warn('No se pudo iniciar la escena 3D:', err);
  canvas.style.display = 'none';
  finishLoading();
}
