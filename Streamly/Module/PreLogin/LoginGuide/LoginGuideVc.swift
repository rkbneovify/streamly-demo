//
//  LoginGuideVc.swift
//  Streamly
//
//  Created by tushar mehta on 23/12/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit

class LoginGuideVc: UIViewController {
    @IBOutlet weak var mainView: GradientView!
    @IBOutlet weak var privacyBtn: UIButton!
    @IBOutlet weak var logoImage: UIImageView!
    @IBOutlet weak var pageControl: UIPageControl!
    @IBOutlet weak var helpBtn: UIButton!
    
    @IBOutlet weak var dontShowBtn: UIButton!
    //    @IBOutlet weak var advancePageControl: AdvancedPageControlView!
    @IBOutlet weak var signInBtn: UIButton!
    @IBOutlet weak var dontShowLbl: UILabel!
//    @IBOutlet weak var pageControl: UIPageControl!
    @IBOutlet weak var collectionView: UICollectionView!
    @IBOutlet weak var scrollingImg: UIImageView!
    var currentPage:Int = 0
    var scrollingTimer = Timer()
    lazy var viewModel: LoginGuideParserContact = {
        return LoginGuideParserContact()
    }()

//    let profileImage = [#imageLiteral(resourceName: "block3"),#imageLiteral(resourceName: "board1"),#imageLiteral(resourceName: "board1")]

    let headingArray = ["dfdfgdfgsdsdsffdsdssddsffdsdfsdsfdssdddsdfssfsd434234233232332432323","dgdgdfdf","trtrtsdfdsfdsdsdsdsdfsdfsrrrt"]
    let subheadingArray = ["trrrttrtrddsdsdsffdsdsfdsdsdsdsddsdsdssdsdsfdsfds","ettet","rttdssddsfdsfdfsdsfdsdsfdsfdfsdsfdsfdsfrtrtrtr"]
    var dataOfLoginGuideVc:LoginGuideViewModel?
    var loginGuideData:[LoginGuideViewModelData]?
    var dataOfLoginGuideVcList: LoginGuideViewModel? {
        didSet {
            self.loginGuideData = self.dataOfLoginGuideVcList?.data
            print("4555544454545tt\(self.dataOfLoginGuideVcList?.data?.count)")
            collectionView.reloadData()
        }
    }

    // MARK: Properties
    override var preferredFocusEnvironments: [UIFocusEnvironment] {
        return [self.privacyBtn]
    }
    override func viewDidLoad() {
        super.viewDidLoad()

//        self.getLoginGuideVcDetails()
        self.initialSetup()
        supportViewdata()
        // Do any additional setup after loading the view.
    }
    
    
    
    func initialSetup(){
          collectionView.register(UINib(nibName: loginGuideCollectionViewCell.identifier, bundle: nil), forCellWithReuseIdentifier: loginGuideCollectionViewCell.identifier)
          collectionView.delegate = self
          collectionView.dataSource = self
          collectionView.setNeedsFocusUpdate()
          collectionView.updateFocusIfNeeded()

//        if #available(tvOS 14.0, *) {
//            pageControl.setIndicatorImage(UIImage(named: "bacgroundImage"), forPage: 0)
//            pageControl.setIndicatorImage(UIImage(named: "gridCell"), forPage: 0)
//
//            pageControl.setIndicatorImage(UIImage(named: "bacgroundImage"), forPage: 0)
//
//        } else {
//            // Fallback on earlier versions
//        }
//           advancePageControl.drawer = ExtendedDotDrawer(numberOfPages: 3,height: 9.0,width: 7.0,
//                                                        space: 7,
//                                                        raduis:50, indicatorColor: UIColor.orange,
////                                                        dotsColor: UIColor.gray,
//                                                        isBordered: true,
//                                                        borderWidth: 0.0,
//                                                        indicatorBorderColor: .clear,
//                                                        indicatorBorderWidth: 3.0)

       }
    
    private func supportViewdata()
    {
        guard let data = dataOfLoginGuideVc else
        {
            return makeApiCall()
            
        }
        print("jjjddfjdfjdfjfdgjfgd\(data)")
    }
    
    private func makeApiCall()
    {
//        self.showSpinner(onView: self.view)
        viewModel.delegate = self
        viewModel.getLoginGuideData()
        self.initVM()
        
        
        
    }
    private func initVM()
    {
        viewModel.contactData1 = { contactModel in
            self.removeSpinner()
            self.dataOfLoginGuideVcList = contactModel
            print("contactmoidelel\(contactModel.count)")
            print("contactmoidele4555l\(self.dataOfLoginGuideVcList?.count)")

            guard let data = self.dataOfLoginGuideVc
            else {
                return
            }
//            self.supportViewSetup(data)
        }
        
    }
    
    
    @IBAction func dontShowBtnTap(_ sender: UIButton) {
        Utility.setBoolInUserDefaultData(value: true, key: Constant.UserDefault.dontShowAgain)
        let loginVc = LoginVC.instantiateFromAppStoryboard(appStoryboard: .Main)
         loginVc.getdata = dataOfLoginGuideVc
         self.navigationController?.pushViewController(loginVc, animated: true)
    }
    
    
    
    @IBAction func signInBtn(_ sender: UIButton) {
        
       let loginVc = LoginVC.instantiateFromAppStoryboard(appStoryboard: .Main)

        loginVc.getdata = dataOfLoginGuideVc
      
        self.navigationController?.pushViewController(loginVc, animated: true)
    }
    
//    private func getLoginGuideVcDetails() {
//        ApiCall.getLoginGuideVcDetail { [weak self] data, status in
//            guard let weak = self else { return }
//            if let data:LoginGuideViewModel = DataParser.parseReponseToModel(data!) {
//                if data.response_code == 200 {
//
//                    print("the data is hgere\(data)")
//                    self?.dataOfLoginGuideVc = data
//                }
//
//            }
//        } failure: { [weak self] error, data, status in
//            guard let weak = self else { return }
//            if let `data` = data, let data:LoginGuideViewModel = DataParser.parseReponseToModel(data) {
//                return
//            }
////            weak.delegate?.apiFailed(error?.localizedDescription ?? "", status ?? 0)
//        }
//    }

}

extension LoginGuideVc {
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if privacyBtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
//                self.configureButtons()
//                self.privacyBtn.layer.borderWidth = 3.0
                //                self.settingsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
//                self.privacyBtn.layer.borderColor = UIColor.white.cgColor
                self.privacyBtn.backgroundColor = .clear
            }, completion: nil)
        }
        else if helpBtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
//                self.configureButtons()
//                self.helpBtn.layer.borderWidth = 3.0
                //                self.searchButton.layer.borderColor = UIColor.secondGradientColor.cgColor
//                self.helpBtn.layer.borderColor = UIColor.white.cgColor
                
                self.helpBtn.backgroundColor = .clear

            }, completion: nil)
        }
        else if collectionView.isFocused {
            if let nextCell = context.nextFocusedView as? loginGuideCollectionViewCell {
                let index = Int(nextCell.accessibilityHint!)!
                DispatchQueue.main.asyncAfter(deadline: .now() + 120) { // 2
                    self.collectionView.scrollToItem(at: IndexPath(item: index, section: 0), at: .centeredVertically, animated: true)
                }
            }
        }
        else if dontShowBtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
//                self.configureButtons()
//                self.dontShowBtn.layer.borderWidth = 3.0
                //                self.searchButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                self.dontShowBtn.layer.borderColor = UIColor.white.cgColor
                
            }, completion: nil)
        }
        else if signInBtn.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
//                self.configureButtons()
//                self.signInBtn.layer.borderWidth = 3.0
                //                self.searchButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                self.signInBtn.layer.borderColor = UIColor.white.cgColor
                self.signInBtn.setTitleColor(.black, for: .normal)
                
            }, completion: nil)
        }

        else {
            coordinator.addCoordinatedAnimations({ () -> Void in
//               self.configureButtons()
                
             
            }, completion: nil)
         
        }
    }
}

extension LoginGuideVc:UICollectionViewDelegate,UICollectionViewDataSource,UICollectionViewDelegateFlowLayout{
    func collectionView(_ collectionView: UICollectionView,
                        canFocusItemAt indexPath: IndexPath) -> Bool {
        return true
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        
        print("ttuuuttutu333223\(loginGuideData?.count ?? 1)")

       return loginGuideData?.count ?? 1
//        return dataOfLoginGuideVc?.data?.count ?? 0
        
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        guard  let cell = collectionView.dequeueReusableCell(withReuseIdentifier: loginGuideCollectionViewCell.identifier, for: indexPath) as? loginGuideCollectionViewCell else{
                   fatalError("No loginGuideCollectionViewCell found...")
               }
        
               
        cell.headingLbl.text = self.loginGuideData?[indexPath.row].title
               cell.subHeadingLbl.text = self.loginGuideData?[indexPath.row].description
        cell.mainImage.sd_setImage(with: URL(string: self.loginGuideData?[indexPath.row].image ?? ""), completed: nil)
       
               
               return cell
    }
    
    @objc func startTimer1(theTimer:Timer){
        UIView.animate(withDuration: 1.0, delay: 0,options: .curveEaseOut, animations:{
            self.collectionView.scrollToItem(at: IndexPath(row: theTimer.userInfo! as! Int, section: 0), at: .centeredHorizontally, animated: false)
        },completion: nil)
    }
    
    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, sizeForItemAt indexPath: IndexPath) -> CGSize {
        return CGSize(width: collectionView.frame.size.width + 20, height: collectionView.frame.size.height)
    }
    
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        pageControl.currentPage = indexPath.row
//        self.collectionView.scrollToItem(at: IndexPath(item:pageControl.currentPage, section: 0), at: .left, animated: true)

        var rowIndex = indexPath.item
        let numberOfRecords:Int = (loginGuideData?.count ?? 1) - 1
        if (rowIndex < numberOfRecords){
            rowIndex = (rowIndex + 1)
        }
        
        else {
            rowIndex = 0
        }
        
        print("cheheheh3434\(rowIndex)")
               
        scrollingTimer = Timer.scheduledTimer(timeInterval: 4, target: self, selector: #selector(startTimer1(theTimer:)), userInfo: rowIndex, repeats: true)
        print("lrlelelele4040004\(indexPath.row)")
        
    }
    
    func scrollViewDidScroll(_ scrollView: UIScrollView) {
//        pageControl.currentPage = indexPath.row

//        var currentIndex = pageControl.currentPage
  
        let offSet = scrollView.contentOffset.x
        let width = scrollView.frame.width
               
//               let currentIndex = Int(offSet / width)
//
        
              
//              collectionView.setContentOffset(CGPoint(x: 200, y: 200), animated: false)
       
               
           }
    }
    

extension LoginGuideVc:HandleError
{
    
    func successAPI() {
        print("9595959594040")
//        self.collectionView.reloadData()
        self.removeSpinner()
    }
    
    func handleError(_ message: String, _ statusCode: Int) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!, Please try again")
    }
    
}
