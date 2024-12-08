// This code will act as guard for the browsers back and
// forward buttons when logging out and in
window.addEventListener('popstate', async (e) => {
    e.stopImmediatePropagation();
    try{
        // to check if application session is still valid
        const res = await fetch('/dashboard')
        if(res.ok){
            window.location.replace(e.state.url);
        }
    }catch(e){
        window.location.replace("/login");
    }
},)