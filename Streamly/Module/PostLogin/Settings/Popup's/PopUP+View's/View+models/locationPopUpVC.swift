//
//  locationPopUpVC.swift
//  Streamly
//
//  Created by Rajpal Singh on 09/08/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit


class locationPopUpVC: UIViewController {

    @IBOutlet weak var selctionLbl: UILabel!
    @IBOutlet weak var currentLocBtn: UIButton!
    @IBOutlet weak var homeAddBtn: UIButton!
    var handleAction: (() -> Void)?
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialSetup()
    }
    
    private func initialSetup() {
        self.currentLocBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.currentLocBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.currentLocBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.currentLocBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.currentLocBtn.setTitleColor(UIColor.white, for: .focused)
        self.currentLocBtn.setTitleColor(UIColor.white, for: .highlighted)
        
        self.homeAddBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.homeAddBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .focused)
        self.homeAddBtn.setBackgroundImage(UIImage.gradientImageWithBounds(bounds: self.homeAddBtn.bounds, colors: [UIColor.firstGradientColor.cgColor,UIColor.secondGradientColor.cgColor]), for: .highlighted)
        self.homeAddBtn.setTitleColor(UIColor.white, for: .focused)
        self.homeAddBtn.setTitleColor(UIColor.white, for: .highlighted)
    }
    
    
    @IBAction func chooseCurrentLoc(_ sender: Any) {
        self.handleAction?()
        self.dismiss(animated: true)
    }
    
    @IBAction func chooseAddress(_ sender: Any) {
        handleAction?()
        self.dismiss(animated: true)
        
    }
    

}
