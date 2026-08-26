export const Component_Inputmask_Date = (className, isParent=false) => {
	if(isParent==true){
		window.parent.$(className).inputmask({
			alias:'dd/mm/yyyy',
			mask: "1-2-y", 
			placeholder: "_", 
			leapday: "-02-29", 
			separator: "-"
		})    
	} else {
		$(className).inputmask({
			alias:'dd/mm/yyyy',
			mask: "1-2-y", 
			placeholder: "_", 
			leapday: "-02-29", 
			separator: "-"
		})    
	}
}

export const Component_Inputmask_Numeric = (className, isParent=false) => {
	if(isParent==true){
		window.parent.$(className).inputmask({
			alias:'numeric',
			digits:'2',
			digitsOptional:false,
			isNumeric: true,      
			prefix:'',
			groupSeparator:".",
			placeholder:'0',
			radixPoint:",",
			autoGroup:true,
			autoUnmask:true,
			onBeforeMask: (value, opts) => {
				return value;
			},
			removeMaskOnSubmit:false
		})
	} else {
		$(className).inputmask({
			alias:'numeric',
			digits:'2',
			digitsOptional:false,
			isNumeric: true,      
			prefix:'',
			groupSeparator:".",
			placeholder:'0',
			radixPoint:",",
			autoGroup:true,
			autoUnmask:true,
			onBeforeMask: (value, opts) => {
				return value;
			},
			removeMaskOnSubmit:false
		})
	}
}

export const Component_Inputmask_Numeric_Flexible = (className, angka, isParent=false) => {
	if(isParent==true){
		window.parent.$(className).inputmask({
			alias:'numeric',
			digits:angka,
			digitsOptional:false,
			isNumeric: true,      
			prefix:'',
			groupSeparator:".",
			placeholder:'0',
			radixPoint:",",
			autoGroup:true,
			autoUnmask:true,
			onBeforeMask: (value, opts) => {
				return value;
			},
			removeMaskOnSubmit:false
		})
	} else {
		$(className).inputmask({
			alias:'numeric',
			digits:angka,
			digitsOptional:false,
			isNumeric: true,      
			prefix:'',
			groupSeparator:".",
			placeholder:'0',
			radixPoint:",",
			autoGroup:true,
			autoUnmask:true,
			onBeforeMask: (value, opts) => {
				return value;
			},
			removeMaskOnSubmit:false
		})
	}
}

export const Component_Scrollbars = (className,scrollx='hidden',scrolly='hidden') => {
	$(className).overlayScrollbars({
		className: "os-theme-dark",
		overflowBehavior : {
			x : scrollx,
			y : scrolly 
		},
		scrollbars : {
			autoHide : 'scroll',
			autoHideDelay : 800,
			snapHandle:true             
		}
	});
}

export const Component_Select2_Account = (className,url=null,addLink=null,titleLink=null,isParent=false) => {
	if(addLink !== null){
		if(isParent==true){
			window.parent.$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
			   "allowAddLink": true,
			   "addLink": addLink,  
			   "linkTitle": titleLink,
		       "dropdownParent": window.parent.$('#modal'),                                        
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
			        return {
			          search: params.term
			        }
			      },
			      "processResults": (data, page) => {
			        return {
			          results: data
			        }
			    },
			  },
			   "templateResult": textSelectAccount,              
			})
		} else {
			$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
			   "allowAddLink": true,
			   "addLink": addLink,  
			   "linkTitle": titleLink,                                        
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
			        return {
			          search: params.term
			        }
			      },
			      "processResults": (data, page) => {
			        return {
			          results: data
			        }
			    },
			  },
			   "templateResult": textSelectAccount,              
			})
		}
	} else {
		if(isParent==true){		
			window.parent.$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
		       "dropdownParent": window.parent.$('#modal'),			   
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
			        return {
			          search: params.term
			        }
			      },
			      "processResults": (data, page) => {
			        return {
			          results: data
			        }
			    },
			  },
			   "templateResult": textSelectAccount,              
			})
		} else {
			$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
			        return {
			          search: params.term
			        }
			      },
			      "processResults": (data, page) => {
			        return {
			          results: data
			        }
			    },
			  },
			   "templateResult": textSelectAccount,              
			})
		}		
	}
}

export const Component_Select2_Item2 = (className,url=null,addLink=null,titleLink=null,isParent=false) => {
	if(url !== null){
	if(addLink !== null){
		if(isParent==true){
			parent.window.$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
		       "dropdownParent": window.parent.$('#modal'),			   
			   "allowAddLink": true,
			   "addLink": addLink,  
			   "linkTitle": titleLink,  
		       "linkSize":"modal-lg",		                                         
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
		              return {
		                search: params.term,
		                page_limit: 10,
		                page: params.page
		              }
			      },
	             "processResults": (data, params) => {
		              params.page = params.page || 1;
		              return {
		                results: data.items,
		                pagination: {
		                  more: (params.page * 10) < data.total
		                }
		              }
	             },
			  },
			   "templateResult": textSelectItem,              
			})
		} else {
			$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
			   "allowAddLink": true,
			   "addLink": addLink,  
			   "linkTitle": titleLink,  
		       "linkSize":"modal-lg",		                                         
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
		              return {
		                search: params.term,
		                page_limit: 10,
		                page: params.page
		              }
			      },
	             "processResults": (data, params) => {
		              params.page = params.page || 1;
		              return {
		                results: data.items,
		                pagination: {
		                  more: (params.page * 10) < data.total
		                }
		              }
	             },
			  },
			   "templateResult": textSelectItem,              
			});
		}
	} else {
		if(isParent==true){
			parent.window.$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
		       "dropdownParent": window.parent.$('#modal'),			   
	           "linkSize":"modal-lg",		   
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
		              return {
		                search: params.term,
		                page_limit: 10,
		                page: params.page
		              }
			      },
	             "processResults": (data, params) => {
		              params.page = params.page || 1;
		              return {
		                results: data.items,
		                pagination: {
		                  more: (params.page * 10) < data.total
		                }
		              }
	             },
			  },
			   "templateResult": textSelectItem,              
			});		
		} else {
			$(className).select2({
			   "allowClear": true,
			   "theme":"bootstrap4",
	           "linkSize":"modal-lg",		   
			   "ajax": {
			      "url": url,
			      "type": "post",
			      "dataType": "json",                                       
			      "delay": 800,
			      "data": (params) => {
		              return {
		                search: params.term,
		                page_limit: 10,
		                page: params.page
		              }
			      },
	             "processResults": (data, params) => {
		              params.page = params.page || 1;
		              return {
		                results: data.items,
		                pagination: {
		                  more: (params.page * 10) < data.total
		                }
		              }
	             },
			  },
			   "templateResult": textSelectItem,              
			});		
		}
	}
	
	} else {
		if(isParent==true){
			window.parent.$(className).select2({
			   "theme":"bootstrap4",
		       "dropdownParent": window.parent.$('#modal')			   			   
			})
		} else {
			$(className).select2({
			   "theme":"bootstrap4"
			})
		}
	}
	
}

export const Component_Select2_Item = (className,url=null,addLink=null,titleLink=null,isParent=false) => {
	if(url !== null){
		if(addLink !== null){
			if(isParent==true){
				parent.window.$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
			       "dropdownParent": window.parent.$('#modal'),			   			   
				   "allowAddLink": true,
				   "addLink": addLink,  
				   "linkTitle": titleLink,                                        
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});
			} else {
				$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "allowAddLink": true,
				   "addLink": addLink,  
				   "linkTitle": titleLink,                                        
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});
			}
		} else {
			if(isParent==true){
				parent.window.$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
			       "dropdownParent": window.parent.$('#modal'),			   			   
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});		
			}else{
				$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});						
			}
		}
	} else {
		if(isParent==true){
			window.parent.$(className).select2({
			   "theme":"bootstrap4",
		       "dropdownParent": window.parent.$('#modal')			   			   
			})
		} else {
			$(className).select2({
			   "theme":"bootstrap4"
			})
		}
	}
}



export const Component_Select2 = (className,url=null,addLink=null,titleLink=null,isParent=false) => {
	if(url !== null){
		if(addLink !== null){
			if(isParent==true){
				parent.window.$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
			       "dropdownParent": window.parent.$('#modal'),			   			   
				   "allowAddLink": true,
				   "addLink": addLink,  
				   "linkTitle": titleLink,                                        
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});
			} else {
				$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "allowAddLink": true,
				   "addLink": addLink,  
				   "linkTitle": titleLink,                                        
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});
			}
		} else {
			if(isParent==true){
				parent.window.$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
			       "dropdownParent": window.parent.$('#modal'),			   			   
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});		
			}else{
				$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});						
			}
		}
	} else {
		if(isParent==true){
			window.parent.$(className).select2({
			   "theme":"bootstrap4",
		       "dropdownParent": window.parent.$('#modal')			   			   
			})
		} else {
			$(className).select2({
			   "theme":"bootstrap4"
			})
		}
	}
}



export const Component_Select2_Tags = (className,url=null,addLink=null,titleLink=null,isParent=false) => {
	if(url !== null){
		if(addLink !== null){
			if(isParent==true){
				parent.window.$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "tags": true,
			       "dropdownParent": window.parent.$('#modal'),			   			   
				   "allowAddLink": true,
				   "addLink": addLink,  
				   "linkTitle": titleLink,                                        
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});
			} else {
				$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "allowAddLink": true,
				   "tags": true,				   
				   "addLink": addLink,  
				   "linkTitle": titleLink,                                        
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});
			}
		} else {
			if(isParent==true){
				parent.window.$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "tags": true,				   
			       "dropdownParent": window.parent.$('#modal'),			   			   
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});		
			}else{
				$(className).select2({
				   "allowClear": true,
				   "theme":"bootstrap4",
				   "tags": true,				   
				   "ajax": {
				      "url": url,
				      "type": "post",
				      "dataType": "json",                                       
				      "delay": 800,
				      "data": (params) => {
				        return {
				          search: params.term
				        }
				      },
				      "processResults": (data, page) => {
				        return {
				          results: data
				        }
				    },
				  }
				});						
			}
		}
	} else {
		if(isParent==true){
			window.parent.$(className).select2({
			   "theme":"bootstrap4",
			   "tags": true,			   
		       "dropdownParent": window.parent.$('#modal')			   			   
			})
		} else {
			$(className).select2({
			   "theme":"bootstrap4",
		       "tags": true			   
			})
		}
	}
}

var textSelectAccount = (param) => {
	if(!param.id) return param.text;
	var $param = $('<span>('+param.kode+') '+param.text+'</span>');
	return $param;
}

var textSelectItem = (param) => {
	if(!param.id){
	  return param.text;
	}
	var $param = $('<div class=\'pb-1\' style=\'border-bottom:1px dotted #86cfda;\'><span class=\'font-weight-bold\' style=\'opacity:.8;\'>'+param.kode+'</span><br/><span>'+param.text+'</span></div>');
	return $param;
}  
