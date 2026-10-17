INSERT INTO USER (userId,userName,password,firstName,lastName,role) VALUES ('1','bsmith','$2y$12$5I0qypHTi/iBkV68TRMeaOf9D92cJJt0QP7yMTHP.J1rjVMw1gATC','Bob','Smith','Admin');
INSERT INTO USER (userId,userName,password,firstName,lastName,role) VALUES ('2','pjones','$2y$12$.xLofId3Zxd8ygjzDiKCzeLwVvmxYb1lNu0LgcVkZklBsxVuP8jfS','Pat','Jones','Customer');
INSERT INTO USER (userId,userName,password,firstName,lastName,role) VALUES ('3','customer3','$2y$12$p.kCEjP2V2qo.Vr6KeTS5.fu9pa87.ZiRKduwopabZCWQ2qbzj6cq','Demo','Three','Customer');
INSERT INTO USER (userId,userName,password,firstName,lastName,role) VALUES ('4','customer4','$2y$12$35wgHOjBc5tq1KeEoUQyFuK8n7gWyg9NJpUYXsNTbCNgAK.uMCbjW','Demo','Four','Customer');
INSERT INTO USER (userId,userName,password,firstName,lastName,role) VALUES ('5','customer5','$2y$12$eEmLAIk6ehzRJxfoqCZZUOZ0p26opdaqPBLV.ItmJWlpiVgsrz.sW','Demo','Five','Customer');

BEGIN TRANSACTION;

INSERT INTO ACCOUNT (userId, accountType, points) VALUES
(1, 'Rewards', 10000),
(2, 'Rewards', 20000),
(3, 'Rewards', 30000),
(4, 'Rewards', 40000),
(5, 'Rewards', 50000);

INSERT INTO GIFTCARD (cardName, cardType, cardValue, points) VALUES
('Amazon $10', 'Retail', 10, 1000),
('Amazon $25', 'Retail', 25, 2500),
('Amazon $50', 'Retail', 50, 5000),
('Target $10', 'Retail', 10, 1000),
('Target $25', 'Retail', 25, 2500),
('Walmart $10', 'Retail', 10, 1000),
('Walmart $25', 'Retail', 25, 2500),
('Best Buy $25', 'Retail', 25, 2500),
('Nike $25', 'Retail', 25, 2500),
('Adidas $25', 'Retail', 25, 2500),
('Starbucks $10', 'Dining', 10, 1000),
('Starbucks $25', 'Dining', 25, 2500),
('Chipotle $10', 'Dining', 10, 1000),
('Chipotle $25', 'Dining', 25, 2500),
('Subway $10', 'Dining', 10, 1000),
('Dominos $25', 'Dining', 25, 2500),
('AMC $15', 'Entertainment', 15, 1500),
('AMC $30', 'Entertainment', 30, 3000),
('Steam $20', 'Entertainment', 20, 2000),
('Steam $50', 'Entertainment', 50, 5000);

COMMIT;