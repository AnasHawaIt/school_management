import './bootstrap';

// Temporary Reverb / Echo test
// Remove this section after the real-time test is completed.

window.Echo
    .channel('library.books')
    .listen('.book.updated', (event) => {
        console.log('================================');
        console.log('BOOK UPDATED - REAL TIME');
        console.log('================================');
        console.log(event);
    });

console.log('Subscribed to library.books');
