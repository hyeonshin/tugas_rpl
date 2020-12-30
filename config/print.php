<!-- function print
	bikin tampilan,
	bikin div hidden
	paste didalam div hidden
 kasih id=button di tombol print

 masukin script di paling bawah -->
<script type="text/javascript">
function printData(){
    var divToPrint = document.getElementById("pdf");
    newWin = window.open(divToPrint.outerhtml);
    newWin.print();
    newWin.class();
    }
$('.button').on('click',function(){
    printData();
})

</script>