
    const canvas = document.getElementById('shader-canvas');
    const gl = canvas.getContext('webgl');

    if (gl) {
      const vertexShaderSource = `
        attribute vec2 position;
        varying vec2 v_texCoord;
        void main() {
          v_texCoord = position * 0.5 + 0.5;
          v_texCoord.y = 1.0 - v_texCoord.y;
          gl_Position = vec4(position, 0.0, 1.0);
        }
      `;

      const fragmentShaderSource = `
        precision highp float;
        varying vec2 v_texCoord;
        uniform float u_time;
        uniform vec2 u_resolution;
        uniform vec2 u_mouse;

        void main() {
            vec2 uv = v_texCoord;
            float time = u_time * 0.1;
            vec2 mouse = u_mouse / u_resolution;
            
            float wave1 = sin(uv.x * 3.0 + time + mouse.x) * 0.5 + 0.5;
            float wave2 = sin(uv.y * 4.0 - time * 1.2 + mouse.y) * 0.5 + 0.5;
            float wave3 = sin((uv.x + uv.y) * 2.5 + time * 0.7) * 0.5 + 0.5;
            
            float intensity = (wave1 + wave2 + wave3) / 3.0;
            
            vec3 color1 = vec3(0.04, 0.04, 0.05); 
            vec3 color2 = vec3(0.12, 0.06, 0.04); 
            
            vec3 finalColor = mix(color1, color2, intensity * 0.35);
            
            float vignette = 1.0 - length(uv - 0.5) * 1.3;
            finalColor *= clamp(vignette, 0.6, 1.0);
            
            gl_FragColor = vec4(finalColor, 1.0);
        }
      `;

      function createShader(gl, type, source) {
        const shader = gl.createShader(type);
        gl.shaderSource(shader, source);
        gl.compileShader(shader);
        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            gl.deleteShader(shader);
            return null;
        }
        return shader;
      }

      const program = gl.createProgram();
      gl.attachShader(program, createShader(gl, gl.VERTEX_SHADER, vertexShaderSource));
      gl.attachShader(program, createShader(gl, gl.FRAGMENT_SHADER, fragmentShaderSource));
      gl.linkProgram(program);
      gl.useProgram(program);

      const buffer = gl.createBuffer();
      gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
      gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);

      const positionLoc = gl.getAttribLocation(program, 'position');
      gl.enableVertexAttribArray(positionLoc);
      gl.vertexAttribPointer(positionLoc, 2, gl.FLOAT, false, 0, 0);

      const timeLoc = gl.getUniformLocation(program, 'u_time');
      const resLoc = gl.getUniformLocation(program, 'u_resolution');
      const mouseLoc = gl.getUniformLocation(program, 'u_mouse');

      let mouseX = 0, mouseY = 0;
      window.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
      });

      function render(time) {
        const width = document.documentElement.clientWidth;
        const height = document.documentElement.clientHeight;
        if (canvas.width !== width || canvas.height !== height) {
          canvas.width = width;
          canvas.height = height;
          gl.viewport(0, 0, canvas.width, canvas.height);
        }
        
        gl.uniform1f(timeLoc, time * 0.001);
        gl.uniform2f(resLoc, canvas.width, canvas.height);
        gl.uniform2f(mouseLoc, mouseX, mouseY);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
        requestAnimationFrame(render);
      }
      requestAnimationFrame(render);
    }
