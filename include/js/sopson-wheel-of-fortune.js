var theWheel = new Winwheel({
    'numSegments' : 8,
    'segments'    :
    [
        {'fillStyle' : '#eae56f', 'text' : 'Shovel'},
        {'fillStyle' : '#89f26e', 'text' : 'Dirt'},
        {'fillStyle' : '#7de6ef', 'text' : 'Cobbelstone'},
        {'fillStyle' : '#e7706f', 'text' : 'Sword 2', 'size' : 4},
        
        {'fillStyle' : '#ea356f', 'text' : 'Shovel', 'size' : 20},
        {'fillStyle' : '#82f26e', 'text' : 'Dirt'},
        {'fillStyle' : '#7dc6ef', 'text' : 'Cobbelstone'},
        {'fillStyle' : '#e7703f', 'text' : 'Sword', 'size' : 4},
    ],							
    'animation' :                   
    {
        'type'     : 'spinToStop',  
        'duration' : 5,
        'spins'    : 8,
        'callbackFinished' : 'alertPrize()',
        'callbackAfter' : 'drawTriangle()'
    },   
    'pointerAngle'   : 90,  
});

console.log(theWheel);

drawTriangle();

function alertPrize()
{
    var winningSegmentNumber = theWheel.getIndicatedSegmentNumber();

    for (var x = 1; x < theWheel.segments.length; x ++)
    {
        theWheel.segments[x].fillStyle = 'gray';
    }

    theWheel.segments[winningSegmentNumber].fillStyle = 'yellow';
    theWheel.draw();

    drawTriangle();
        
	var winningSegment = theWheel.getIndicatedSegment();
	
	alert("You have won " + winningSegment.text + "!");
}

function drawTriangle()
{
	var ctx = theWheel.ctx;
 
    ctx.strokeStyle = 'navy';  
    ctx.fillStyle   = 'aqua';  
    ctx.lineWidth   = 2;
    ctx.beginPath();

    ctx.moveTo(750, 274);
    ctx.lineTo(750, 326);
    ctx.lineTo(710, 300);
    ctx.lineTo(750, 275);
    ctx.stroke();
    ctx.fill();
}
