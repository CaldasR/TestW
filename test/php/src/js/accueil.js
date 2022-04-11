
// les console.log doivent etre delete en prod
$( document ).ready(function() {

    $("button#disconnect").click(function() {
	  	$.post( "ajax/disconnect.php", function( data ) {
		  window.location.href = 'index.php';
		});
	});

    function loadCountries() {
    	$.get( "ajax/getCountries.php", function( data ) {
			try {
				var countries = JSON.parse(data);
				for (var i = 0; i < countries.length; i++) {
					$("select#input_country").append("<option value='"+countries[i].iso3+"'"+(countries[i].iso3 === 'ITA' ? 'selected="selected"' : '')+">"+countries[i].name+"</option>");
				}
			} catch (error) {
				console.error(error);
			}
		});
    }

    function loadProducts() {
    	$.get( "ajax/getProducts.php", function( data ) {
			try {
				products = JSON.parse(data)				
				for (var i = 0; i < products.length; i++) {
					$("select#input_product").append("<option value='"+products[i].id+"'>"+products[i].name+"</option>");
				}
			} catch (error) {
				console.error(error);
			}
		});
    }

    function loadProviders() {
    	$.get( "ajax/getProviders.php", function( data ) {
			try {
				var providers = JSON.parse(data)
				$('table#providers tbody').empty();
				for (var i = 0; i < providers.length; i++) {
					providers[i].type = 'provider';
					var customTr = $("<tr>").data( "data",  providers[i]);
					$(customTr).append('<td id="canOpen">'+providers[i].name+'</td><td>'+providers[i].address+'</td><td>'+providers[i].country+'</td>');
					$('table#providers tbody').append(customTr);
				}

				// Pour une nouvelle transa
				$("select#input_provider option").each(function() {
				    $(this).remove();
			   	});
			   	$("select#input_provider").append("<option value='-1' selected></option>");
				for (var i = 0; i < providers.length; i++) {
					$("select#input_provider").append("<option value='"+providers[i].id+"'>"+providers[i].name+"</option>");
				}
			} catch (error) {
				console.error(error);
			}
		});
    }

    function loadClients() {
    	$.get( "ajax/getClients.php", function( data ) {
			try {
				var clients = JSON.parse(data)
				$('table#clients tbody').empty();
				for (var i = 0; i < clients.length; i++) {
					clients[i].type = 'client';
					var customTr = $("<tr>").data( "data",  clients[i]);
					$(customTr).append('<td id="canOpen">'+clients[i].name+'</td><td>'+clients[i].address+'</td><td>'+clients[i].balance+'</td><td>'+clients[i].country+'</td>');
					$('table#clients tbody').append(customTr);
				}
			} catch (error) {
				console.error(error);
			}
		});
    }
	
    function loadCompanies() {
		$.get( "ajax/getCompanies.php", function( data ) {
			try {
				var companies = JSON.parse(data)
				$('table#companies tbody').empty();

				for (var i = 0; i < companies.length; i++) {
					companies[i].type = 'company';
					var customTr = $("<tr>").data( "data",  companies[i]);
					$(customTr).append('<td id="canOpen">'+companies[i].name+'</td><td>'+companies[i].balance+'</td><td>'+companies[i].country+'</td>');
					$('table#companies tbody').append(customTr);
				}

				// Pour une nouvelle transa
				$("select#input_company option").each(function() {
				    $(this).remove();
			   	});
			   	$("select#input_company").append("<option value='-1' selected></option>");
				for (var i = 0; i < companies.length; i++) {
					$("select#input_company").append("<option value='"+companies[i].id+"'>"+companies[i].name+"</option>");
				}
			} catch (error) {
				console.error(error);
			}
		});
  	}

  	function loadTransactions(_type = null, _id = null) {
  		$.get( "ajax/getTransactions.php", { type: _type, id: _id }).done(function( data ) {
  			try {
  				var target = 'table#transaction tbody';
  				var transactions = JSON.parse(data);

  				// Dans la fiche detail
  				if (_type != null && _id != null) {
  					var target = 'table#transactionsDetails tbody';
  					switch (_type) {
  						case 'company':
  							$('table#transactionsDetails th#swap').text('Fournisseur / Client');
  						break;
  						default:
  							$('table#transactionsDetails th#swap').text('Compagnie');
  						break;
  					}
	  			} 

				$(target).empty();
				if (transactions.length === 0) {
  					$(target).append('<tr><td colspan="7">Aucune transaction</td></tr>');
  				}
				for (var i = 0; i < transactions.length; i++) {
					transactions[i].type = 'product';
					var customTr = $("<tr>").data( "data",  transactions[i]);
					$(customTr).append(
						'<td>'+transactions[i].product_name+'</td>'+
						// condition if (!fiche detail) { print fournisseur->compagnie && compagnie->client } else { if (fournisseur) { print fournisseur } else { print compagnie } }
						(
							_type === null && _id === null ? 
							(
								'<td>'+(transactions[i].provider_name != null && transactions[i].company_name != null ? (transactions[i].provider_name + ' → ' + transactions[i].company_name) : "")+'</td>'+
								'<td>'+(transactions[i].company_name != null && transactions[i].client_name != null ? (transactions[i].company_name + ' → ' + transactions[i].client_name) : "")+'</td>'
							)
							:
							(
								_type === 'provider' ? "<td>"+transactions[i].company_name+"</td>" : 
								(
									_type === 'company' ? (
										transactions[i].client_name != null ? "<td>"+transactions[i].client_name+"</td>" : "<td>"+transactions[i].provider_name+"</td>"
									) 
									: 
									"<td>"+transactions[i].company_name+"</td>"
								)
								
							)
						)+
						'<td>'+(transactions[i].employee_name ?? "")+'</td>'+
						'<td>'+transactions[i].price+'</td>'+
						'<td>'+transactions[i].quantity+'</td>'+
						'<td>'+transactions[i].datetime+'</td>'
					);
					$(target).append(customTr);
				}
			} catch (error) {
				console.error(error);
			}
		});
  	}	
	
  	function loadStocks(_type = null, _id = null, _newTransa = false) {
  		$.get( "ajax/getStocks.php", { type: _type, id: _id }).done(function( data ) {
  			try {
  				var target = 'table#stocks tbody';
  				var stocks = JSON.parse(data);

  				// Dans la fiche detail
  				if (_newTransa) {
  					return loadNewTransaStock(stocks);
  				}

  				if (_type != null && _id != null) {
  					var target = 'table#stocksDetails tbody';
	  			} 

				$(target).empty();
				if (stocks.length === 0) {
  					$(target).append('<tr><td colspan="7">Aucun stock</td></tr>');
  				}
				for (var i = 0; i < stocks.length; i++) {
					stocks[i].type = 'product';
					var customTr = $("<tr>").data( "data",  stocks[i]);
					$(customTr).append(
						'<td id="canOpen">'+stocks[i].name+'</td>'+
						'<td>'+stocks[i].price+'</td>'+
						'<td>'+stocks[i].tax+'% </td>'+
						(_type == null && _id == null ? ('<td>'+(stocks[i].id_company == null ? ('Fournisseur : '+stocks[i].owner_name) : ('Compagnie : '+stocks[i].owner_name))+'</td>') : '')
						+'<td>'+stocks[i].number+'</td>'
					);
					$(target).append(customTr);
				}
			} catch (error) {
				console.error(error);
			}
		});
  	}

  	function loadNewTransaStock(stocks) {

		$('#boxChoice').show();

		$("#containerCheckBox").empty();

		if (stocks.length === 0) {
			$("#containerCheckBox").append('Aucun stock');
		}

		for (var i = 0; i < stocks.length; i++) {
			var customInput = $('<input type="checkbox" name="productChoice" id="'+stocks[i].id+'" />').data( "data",  stocks[i]);
			$("#containerCheckBox").append(customInput);
			$("#containerCheckBox").append(
				'<p class="name">'+stocks[i].name+'</p>'+
				'<p>Prix: '+stocks[i].price+'</p>'+
				'<p>Tax: '+stocks[i].tax+' % </p>'+
				'<p>Stock dispo: '+stocks[i].number+'</p>'+
				'<br>'
			);
		}
  	}
	
	setTimeout(function(){
		loadCountries();
		loadProducts();
  		loadProviders();
  		loadClients();
		loadCompanies();
		loadStocks();
		loadTransactions();
  	}, 300);

	$('table tbody').on('click', 'td#canOpen', function(elem) {
	    openModal($(elem.target).parent().data('data'));
	});

  	function openModal(data) {
  		console.log(data);
  		$('a.pen').show();
		$('#containerEdit > span').hide();
		$('.read').show();
		$('.edit').hide();
  		
  		$('#persoDetail span#name').text(data.name);
  		$('#persoDetail input#input_name').val(data.name);

  		$('#persoDetail input#id').val(data.id).data( "data", data);

  		// masque les differentes infos
  		$('p.address').hide();
  		$('div.country').hide();
  		$('p.balance').hide();
  		$('table#stocksDetails').hide();
  		$('table#transactionsDetails').hide();
  		$('p.price').hide();
  		$('p.tax').hide();
  		$('p#newStock').hide();
  		$('p#newTransaction').hide();
  		
  		switch (data.type) {
		  	case 'provider':
		  		$('#persoDetail span#type').text("Fournisseur: ");

		  		$('#persoDetail span#address').text(data.address);
  				$('#persoDetail input#input_address').val(data.address);

  				$('#persoDetail span#country').text(data.country);
  				$('select#input_country option').each(function(index, elem) {
  					if ($(elem).html() === data.country) {
  						$(elem).prop('selected', true);
  					}
  				});

  				loadStocks(data.type, data.id);
  				loadTransactions(data.type, data.id);

				$('table#stocksDetails').show();
				$('p#newStock').show();
		  		$('p.address').show();
		  		$('div.country').show();
		  		$('table#transactionsDetails').show();
		    break;
		    case 'client':
		    	$('#persoDetail span#type').text("Client: ");

		  		$('#persoDetail span#address').text(data.address);
  				$('#persoDetail input#input_address').val(data.address);

  				$('#persoDetail span#balance').text(data.balance);
  				$('#persoDetail input#input_balance').val(data.balance);
  				
  				$('#persoDetail span#country').text(data.country);
  				$('select#input_country option').each(function(index, elem) {
  					if ($(elem).html() === data.country) {
  						$(elem).prop('selected', true);
  					}
  				});

  				loadTransactions(data.type, data.id);

		  		$('p.address').show();
		  		$('div.country').show();
		  		$('p.balance').show();
		  		$('p#newTransaction').show();
		  		$('table#transactionsDetails').show();
		    break;
			case 'company':
				$('#persoDetail span#type').html("Compagnie: ");
				$('#persoDetail input#id').val(data.id);

				$('#persoDetail span#balance').text(data.balance);
  				$('#persoDetail input#input_balance').val(data.balance);

				$('#persoDetail span#country').text(data.country);
  				$('select#input_country option').each(function(index, elem) {
  					if ($(elem).html() === data.country) {
  						$(elem).prop('selected', true);
  					}
  				});

  				loadStocks(data.type, data.id);
  				loadTransactions(data.type, data.id);

  				$('p#newTransaction').show();
				$('table#stocksDetails').show();
				$('table#transactionsDetails').show();
  				$('p.balance').show();
		  		$('div.country').show();
			break;
			case 'product':
				$('#persoDetail span#type').html("Produit: ");

				$('#persoDetail span#price').text(data.price);
  				$('#persoDetail input#input_price').val(data.price);

				$('#persoDetail span#tax').text(data.tax);
  				$('#persoDetail input#input_tax').val(data.tax);

				$('p.price').show();
				$('p.tax').show();
			break;
		}

		if (isAdmin != true) {
			
		}
  		$('#detailContainer').show();

  	}

  	// Valide l'edition & enregistre
  	$("#containerEdit > span #valid").click(function() {

  		$('a.pen').show();
		$('#containerEdit > span').hide();
		$('.read').show();
		$('.edit').hide();

		var id = $('#persoDetail input#id').val();
  		var data = $('#persoDetail input#id').data('data');
  		console.log(data);

  		switch (data.type) {
		  	case 'provider':
		  		// recup les datas edites
		  		var newName = $('#persoDetail input#input_name').val() || data.name;
  				var newAddress = $('#persoDetail input#input_address').val() || data.address;
				var newcountry = $('select#input_country option:selected').text();
				var newcountryIso = $('select#input_country option:selected').val();

				// faire une gestion d'erreurs pour les inputs

				if (newName != data.name || newAddress != data.address || newcountry != data.country) {
					$.post( "ajax/updateProvider.php", { 'id': data.id, 'name': newName, 'address': newAddress, 'countryIso': newcountryIso }).done(function( data ) {
						if (parseInt(data) > 0) {
							loadProviders();
							loadStocks();
							$('#detailContainer').hide();
						} else {
							alert("Impossible de mettre a jour le fournisseur");
						}
					});
				}
		    break;
		    case 'client':
		  		// recup les datas edites
		  		var newName = $('#persoDetail input#input_name').val() || data.name;
  				var newAddress = $('#persoDetail input#input_address').val() || data.address;
				var newcountry = $('select#input_country option:selected').text();
				var newcountryIso = $('select#input_country option:selected').val();
				var newBalance = parseInt($('#persoDetail input#input_balance').val()) || data.balance;
				// faire une gestion d'erreurs pour les inputs

				if (newName != data.name || newAddress != data.address || newcountry != data.country || newBalance != data.balance) {
					$.post( "ajax/updateClient.php", { 'id': data.id, 'name': newName, 'address': newAddress, 'countryIso': newcountryIso, 'balance': newBalance }).done(function( data ) {
						if (parseInt(data) > 0) {
							loadClients();
							loadStocks();
							$('#detailContainer').hide();
						} else {
							alert("Impossible de mettre a jour le client");
						}
					});
				}
		    break;
			case 'company':
				var newName = $('#persoDetail input#input_name').val() || data.name;
  				var newBalance = parseInt($('#persoDetail input#input_balance').val()) || data.balance;
				var newcountry = $('select#input_country option:selected').text();
				var newcountryIso = $('select#input_country option:selected').val();

				// faire une gestion d'erreurs pour les inputs

				if (newName != data.name || newBalance != data.balance || newcountry != data.country) {
					$.post( "ajax/updateCompany.php", { 'id': data.id, 'name': newName, 'balance': newBalance, 'countryIso': newcountryIso }).done(function( data ) {
						if (parseInt(data) > 0) {
							loadCompanies();
							loadStocks();
							$('#detailContainer').hide();
						} else {
							alert("Impossible de mettre a jour la compagnie");
						}
					});
				}
			break;
			case 'product':
				var newName = $('#persoDetail input#input_name').val() || data.name;
				var newPrice = parseInt($('#persoDetail input#input_price').val()) || data.price;
  				var newTax = parseInt($('#persoDetail input#input_tax').val()) || data.tax;

  				// faire une gestion d'erreurs pour les inputs

				if (newName != data.name || newPrice != data.price || newTax != data.tax) {
					$.post( "ajax/updateProduct.php", { 'id': data.id, 'name': newName, 'price': newPrice, 'tax': newTax }).done(function( data ) {
						if (parseInt(data) > 0) {
							loadStocks();
							$('#detailContainer').hide();
						} else {
							alert("Impossible de mettre a jour le produit");
						}
					});
				}
			break;
		}
  	});

  	// Valide l'ajout de stock
  	$("#persoStock span #valid").click(function() {
  		var data = $('#persoDetail input#id').data( "data");

  		var newQuantity = $('#persoStock input#input_quantity').val();
		var newProductId = $('select#input_product option:selected').val();

		// faire une gestion d'erreurs pour les inputs

		$.post( "ajax/updateStock.php", { 'id_provider': data.id, 'id_product': newProductId, 'quantity': parseInt(newQuantity || 0) }).done(function( dataRetour ) {
			if (parseInt(dataRetour) > 0) {
				loadStocks();
				loadStocks(data.type, data.id);
				$('#stockContainer').hide();
			} else {
				alert("Impossible de mettre a jour le fournisseur");
			}
		});
  	});

  	// Swap encore le mode edition et readonly
  	$("a.pen, #containerEdit > span #refuse").click(function() {
		if ($('.read').is(":visible")) {
			$('a.pen').hide();
			$('#containerEdit > span').show();
			$('.read').hide();
			$('.edit').show();
		} else {
			$('a.pen').show();
			$('#containerEdit > span').hide();
			$('.read').show();
			$('.edit').hide();
		}
	});
	
	// X ferme la modal
	$("#persoDetail a.close-modal").click(function() {
	  	$('#detailContainer').hide();
	});

	// Esc ferme la modal
	$(document).keyup(function(e) {
	     if (e.key === "Escape") {
	        if ($('#detailContainer').is(":visible")) {
	        	if ($('#stockContainer').is(":visible")) {
	        		$('#stockContainer').hide();
	        	} else {
	        		if ($('#transactionContainer').is(":visible")) {
		        		$('#transactionContainer').hide();
		        	} else {
		        		$('#detailContainer').hide();	
		        	}
	        	}
	        }
	    }
	});

	// ouverture de la modal de gestion des stocks
	$('table#stocksDetails caption p').click(function (){
		$('#stockContainer').show();
	});

	// X ferme la modal
	$("#persoStock a.close-modal, #persoStock span #refuse").click(function() {
	  	$('#stockContainer').hide();
	});

	// force certain input a l'integer only
	$('input.integerOnly').keyup(function(elem) {
		var input = elem.target;
		$(input).val(parseInt($(input).val()) || 0);
	})




	// modal transaction

	// ouverture de la modal de nouvelle transaction
	$('table#transactionsDetails caption p').click(function (){
		var data = $('#persoDetail input#id').data('data');
		
		
		$('div#chooseCompany').hide();
		$('div#chooseProvider').hide();
		if (($('#persoDetail input#id').data('data')).type == 'client') {
			$('div#chooseCompany').show();
			$('select#input_company option').each(function(index, elem) {
				if ($(elem).val() === "-1") {
					$(elem).prop('selected', true);
				}
			});
		} else {
			$('div#chooseProvider').show();
			$('select#input_provider option').each(function(index, elem) {
				if ($(elem).val() === "-1") {
					$(elem).prop('selected', true);
				}
			});
		}
		

		$('#boxChoice').hide();
		$('#transactionContainer').show();
		console.log($('#persoDetail input#id').data( "data"));
	});

	// X ferme la modal
	$("#persoTransaction a.close-modal, #persoTransaction #boxChoice span #refuse").click(function() {
	  	$('#transactionContainer').hide();
	});

	// Choix du fournisseur
	$('select#input_provider, select#input_company').on('change', function() {
		$('#boxChoice').hide();
		$('#persoTransaction #boxChoice span#canValid').hide();
		$('#persoTransaction #boxChoice #total').text('0 €');

		if (this.value != -1) {
			if (($('#persoDetail input#id').data('data')).type == 'client') {
				loadStocks('company', this.value, true);
			} else {
				loadStocks('provider', this.value, true);
			}
			
			
		}
	});

	// choix du produit a acheter
	$(document).on('click', '#boxChoice input[type="checkbox"]', function() {      
	    $('input[type="checkbox"]').not(this).prop('checked', false);
	    countTotal();
	});

	// changement du nombre a acheter
	$('#boxChoice .quantityToBuy #input_quantity').keyup(function(elem) {
		countTotal();
	})

	// calcul du montant total de la transa
	function countTotal() {
		$('p.error').text("").hide();
		$('#persoTransaction #boxChoice span#canValid').hide();
		var selectedProduct = $('#boxChoice input[type="checkbox"]:checked').data('data');
		var quantityToBuy = parseInt($('#boxChoice .quantityToBuy #input_quantity').val()) || 0;

		if (selectedProduct != undefined && quantityToBuy > 0) {

			var price = parseInt(selectedProduct.price) || 0;
			var tax = parseInt(selectedProduct.tax) || 0;

			var price2 = price + (price * tax / 100);
			var total = price2 * quantityToBuy;
			
			total = Math.round(total * 100) / 100;
			$('#persoTransaction #boxChoice #total').text(total+' €');
			$('#persoTransaction #boxChoice span#canValid').show();
			return ;
		}
		$('#persoTransaction #boxChoice #total').text('0 €');
	}

	$('#persoTransaction #boxChoice span#canValid #valid').click(function() {

		var quantityToBuy = parseInt($('#boxChoice .quantityToBuy #input_quantity').val()) || 0;
		var selectedProduct = $('#boxChoice input[type="checkbox"]:checked').data('data');
		var dataCompany = $('#persoDetail input#id').data( "data");
		// produit obligatoire & nombre > 0
		if (selectedProduct != undefined && quantityToBuy > 0) {
			$.post( "ajax/addTransaction.php", { 'quantityToBuy': quantityToBuy, 'selectedProduct': selectedProduct, 'dataCompany': dataCompany }).done(function( dataRetour ) {
				try {
					var dataRetour = JSON.parse(dataRetour);
					if (parseInt(dataRetour['return']) > 0) {
						var data = $('#persoDetail input#id').data('data');
						loadStocks(data.type, data.id);
  						loadTransactions(data.type, data.id);
  						loadStocks();
  						loadTransactions();
  						loadClients();
  						loadCompanies();
  						$('#persoDetail span#balance').text(dataRetour['message']); //cheat, je dois reload la balance
						$('#transactionContainer').hide();
					} else {
						$('p.error').text(dataRetour['message']).show();
					}
				} catch (error) {
					console.error(error);
				}
			});
		}
	});

});

