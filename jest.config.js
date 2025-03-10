export default {
    transform: {
      '^.+\\.js$': 'babel-jest',
    },
    moduleNameMapper: {
      '\\.css$': 'identity-obj-proxy', // Simulamos los archivos CSS
      'jquery': 'jest-mock',  // Simulamos jQuery para evitar el error de "jQuery no está definido"
    },
    testEnvironment: 'jest-environment-jsdom', // Usa jsdom para simular el entorno del navegador
    setupFilesAfterEnv: ['@testing-library/jest-dom'], // Carga jest-dom para mejorar las aserciones
  };
  