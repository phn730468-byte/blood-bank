function validateForm(f){
  const age=f.age?parseInt(f.age.value):18;
  if(age<18||age>65){alert('Donor age must be between 18 and 65');return false;}
  if(f.contact&&!/^[0-9]{10}$/.test(f.contact.value)){alert('Enter a valid 10-digit contact number');return false;}
  return true;
}
document.addEventListener('DOMContentLoaded',()=>{
  const c=document.getElementById('cdate'),x=document.getElementById('edate');
  if(c&&x)c.addEventListener('change',()=>{const d=new Date(c.value);d.setDate(d.getDate()+35);x.value=d.toISOString().slice(0,10);});
});
