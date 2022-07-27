//
//  DeviceInfoGridCell.swift
//  Streamly
//
//  Created by Aditya Joshi on 26/07/22.
//  Copyright © 2022 Rohit Singh. All rights reserved.
//

import UIKit

class DeviceInfoGridCell: UICollectionViewCell {
    @IBOutlet weak var deviceNamePH: UILabel!
    @IBOutlet weak var deviceNameLabel: UILabel!
    @IBOutlet weak var bgView: UIView!
    @IBOutlet weak var deviceImage: UIImageView!
    @IBOutlet weak var deviceTypePHLabel: UILabel!
    @IBOutlet weak var deviceTypLabel: UILabel!
    
    static let identifier = String(describing: DeviceInfoGridCell.self)
    override func awakeFromNib() {
        super.awakeFromNib()
        configureCell()
    }
    
    private func configureCell() {
        deviceNamePH.text = "Device Name:"
        deviceNameLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 22.0)
        deviceTypePHLabel.text = "Device Type:"
        deviceTypLabel.font = UIFont.init(name: "HelveticaNeue-Medium", size: 22.0)
        deviceNamePH.font = UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        deviceTypePHLabel.font = UIFont.init(name: "HelveticaNeue-Light", size: 28.0)
        bgView.layer.cornerRadius = 10
        bgView.layer.borderWidth = 1.0
        
    }
    
    func setupData(model: DeviceInfo) {
        deviceTypLabel.text = model.deviceType ?? ""
        deviceNameLabel.text = model.deviceName
        deviceImage.sd_setImage(with: URL(string: model.deviceImg ?? ""), completed: nil)
        
    }
    
    // MARK: UIFocusEnvironment
    
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        /*
         Update the label's alpha value using the `UIFocusAnimationCoordinator`.
         This will ensure all animations run alongside each other when the focus
         changes.
         */
        coordinator.addCoordinatedAnimations({
            if !self.isFocused {
                self.bgView.backgroundColor = UIColor.init(red: 89/255, green: 97/255, blue: 100/255, alpha: 1.0)
                self.bgView.borderWidth = 1.0
                self.bgView.borderColor = .clear
                //    self.backgroundColor = UIColor.gray
            }
            else {
                self.bgView.borderWidth = 1.0
                self.bgView.borderColor = .white
                self.bgView.backgroundColor = UIColor.lightGray
            }
        }, completion: nil)
    }
}
