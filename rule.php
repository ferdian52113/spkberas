								foreach ($rule_based_system as $rule) {
                                    if($stabilitas_harga==$rule->stabilitas_harga AND $musim==$rule->musim AND $bencana==$rule->bencana AND $hama==$rule->hama){
                                        $namarekomendasi = $rule->id_rekomendasi;
                                        $rekomendasi1 = $rule->rekomendasi_1;
                                        $rekomendasi2 = $rule->rekomendasi_2;
                                        $rekomendasi3 = $rule->rekomendasi_3; 
                                    }
                                    echo "if ".$rule->stabilitas_harga." and ".$rule->musim." and ".$rule->bencana." and ".$rule->hama." then ".$rule->id_rekomendasi;
                                    echo '<br>';
                                }

                            if($stabilitas_harga=='Stabil') {
                            	if($musim=='Penghujan') {
                            		if($bencana=='Tidak Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R1';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R2';
                            			}
                            		} else if ($bencana=='Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R3';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R4';
                            			}
                            		}
                            	} else if ($musim=='Kemarau') {
                            		if($bencana=='Tidak Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R5';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R6';
                            			}
                            		} else if ($bencana=='Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R7';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R8';
                            			}
                            		}
                            	}
                            } else if ($stabilitas_harga=='Tidak Stabil') {
                            	if($musim=='Penghujan') {
                            		if($bencana=='Tidak Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R9';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R10';
                            			}
                            		} else if ($bencana=='Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R11';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R12';
                            			}
                            		}
                            	} else if ($musim=='Kemarau') {
                            		if($bencana=='Tidak Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R13';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R14';
                            			}
                            		} else if ($bencana=='Ada Bencana') {
                            			if($hama=='Tidak Ada Hama') {
                            				$namarekomendasi = 'R15';
                            			} else if ($hama=='Ada Hama') {
                            				$namarekomendasi = 'R16';
                            			}
                            		}
                            	}
                        	}