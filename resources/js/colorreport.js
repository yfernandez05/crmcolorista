// colorsreport.js

const baseColors = [
    '#242424',    // Color 1
    '#f68866',    // Color 2
    '#22c1c3',    // Color 3
    '#d069be',    // Color 4
    '#ec7063',    // Color 5
    '#69b070',    // Color 6
    '#117a65',    // Color 7
    '#f39c12',    // Color 8
    '#e74c3c',    // Color 9
    '#475577',    // Color 10
    '#8e44ad',    // Color 11
    '#2c3e50',    // Color 12
    '#bdc3c7',    // Color 13
    '#95a5a6',    // Color 14
    '#34495e',    // Color 15
    '#c0392b',    // Color 16
    '#26a65b',    // Color 17
    '#9b59b6',    // Color 18
    '#e67e22',    // Color 19
    '#d38400',    // Color 20
    '#ff5733',    // Color 21
    '#33ff57',    // Color 22
    '#5733ff',    // Color 23
    '#ff33a1',    // Color 24
    '#a1ff33',    // Color 25
    '#33a1ff',    // Color 26
    '#ff8c33',    // Color 27
    '#33ff8c',    // Color 28
    '#8c33ff',    // Color 29
    '#ff338c',    // Color 30
    '#338cff',    // Color 31
    '#33ffbd',    // Color 32
    '#ffbd33',    // Color 33
    '#bd33ff',    // Color 34
    '#ff3366',    // Color 35
    '#6633ff',    // Color 36
    '#33ff66',    // Color 37
    '#66ff33',    // Color 38
    '#3366ff',    // Color 39
    '#ff6633',    // Color 40
];


function hexToRgba(hex, opacity) {
    hex = hex.replace('#', '');
    let r = parseInt(hex.substring(0, 2), 16);
    let g = parseInt(hex.substring(2, 4), 16);
    let b = parseInt(hex.substring(4, 6), 16);
    return `rgba(${r}, ${g}, ${b}, ${opacity})`;
}

// Generación de colores con diferentes opacidades
export const bgColors = baseColors.map(color => color); 

export const bgBorder = baseColors.map(color => {
    return hexToRgba(color, 0.8);
});