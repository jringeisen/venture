const backgroundImage = document.getElementById('grades');
backgroundImage.style.backgroundImage = "url('../assets/images/grades-2.jpg')";

const lowerSchool = document.getElementById('lower-school');
lowerSchool.classList.remove('hidden');

const middleSchool = document.getElementById('middle-school');
const highSchool = document.getElementById('high-school');

document.getElementById('grade-lower').addEventListener('click', () => {
    backgroundImage.style.backgroundImage = "url('../assets/images/grades-2.jpg')";

    if (lowerSchool.classList.contains('hidden')) {
        lowerSchool.classList.remove('hidden');
    }

    if (!middleSchool.classList.contains('hidden')) {
        middleSchool.classList.add('hidden');
    }

    if (!highSchool.classList.contains('hidden')) {
        highSchool.classList.add('hidden');
    }
});

document.getElementById('grade-middle').addEventListener('click', () => {
    backgroundImage.style.backgroundImage = "url('../assets/images/grades-3.jpg')";

    if (middleSchool.classList.contains('hidden')) {
        middleSchool.classList.remove('hidden');
    }

    if (!lowerSchool.classList.contains('hidden')) {
        lowerSchool.classList.add('hidden');
    }

    if (!highSchool.classList.contains('hidden')) {
        highSchool.classList.add('hidden');
    }
});

document.getElementById('grade-high').addEventListener('click', () => {
    backgroundImage.style.backgroundImage = "url('../assets/images/grades-1.jpg')";

    if (highSchool.classList.contains('hidden')) {
        highSchool.classList.remove('hidden');
    }

    if (!lowerSchool.classList.contains('hidden')) {
        lowerSchool.classList.add('hidden');
    }

    if (!middleSchool.classList.contains('hidden')) {
        middleSchool.classList.add('hidden');
    }
});

toggleFAQ = (event) => {
    for (event of event.children) {
        if (event.tagName === 'UL') {
            event.classList.toggle('hidden');
        }
    }
};

toggleMobileMenu = () => {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
};

toggleResponse = (element) => {
    const background = document.getElementById('opaque-background');
    background.classList.toggle('hidden');

    for (child of element.children) {
        if (child.tagName === 'DIV') {
            child.classList.toggle('hidden');
        }
    }

    element.classList.toggle('z-40');
};

handleClickOutside = (element) => {
    element.classList.toggle('hidden');

    const questionCards = document.getElementById('questionCards');

    for (child of questionCards.children) {
        if (child.classList.contains('z-40')) {
            child.classList.toggle('z-40');
        }

        for (grandchild of child.children) {
            if (grandchild.tagName === 'DIV' && !grandchild.classList.contains('hidden')) {
                grandchild.classList.toggle('hidden');
            }
        }
    }
};
