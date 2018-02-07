
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
        
    var name = jQuery('.spin-info-container h5').text();
    var winningSegment = theWheel.getIndicatedSegment();
    
    jQuery.ajax({
        type: 'POST',
        url: sopson_ajax_url,
        data: {
            action: 'update_last_spin',
            item_name: winningSegment.text,
            player_name: name,
            item_index: winningSegmentNumber
        },
        success: function(data, status, xhr) 
        {   
            console.log(data);
            console.log(status);
            console.log(xhr);
            document.getElementById("wof-result").innerHTML = winningSegment.text;
            var inst = jQuery('[data-remodal-id=modal]').remodal();
            inst.open();
        },
        error: function(data, status, xhr)
        {
            console.log(data);
            console.log(status);
            console.log(xhr);
            document.getElementById("wof-result").innerHTML = data;
            document.getElementById("wof-result").style.background = "rgba(255, 0, 0, .6)";
        }
    });
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