const commaSeparateNumber = (val) => {

	var i = val;

	if(i<0) val = val*-1;

	var number_string = val.toString(),
	    split = number_string.split('.'),
	    sisa  = split[0].length % 3,
	    rupiah  = split[0].substr(0, sisa),
	    ribuan  = split[0].substr(sisa).match(/\d{1,3}/gi);

	if (ribuan) {
		var separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

	if(i<0) rupiah = `-${rupiah}`;

	return rupiah;

}

const reverseCommaNumber = (val) => {
    nomor = parseFloat(val.split('.').join('').replace(",", "."));
//    nomor = nomor.replace(/,/g, '.');    
	return nomor;

}